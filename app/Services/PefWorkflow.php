<?php

namespace App\Services;

use App\Exceptions\InvalidTransitionException;
use App\Models\Pef;
use App\Models\PefApproval;
use App\Models\Section;
use App\Models\User;
use App\Notifications\PefStatusChanged;
use Illuminate\Support\Facades\DB;

class PefWorkflow
{
    const STAGE_ROLE_MAP = [
        'pending_advising'  => 'adviser',
        'pending_admission' => 'admission',
        'pending_registrar' => 'registrar',
        'pending_cashier'   => 'cashier',
    ];

    public function submit(Pef $pef, User $actor, PefValidator $validator): void
    {
        if (! in_array($pef->status, [Pef::STATUS_DRAFT, Pef::STATUS_RETURNED], true)) {
            throw new InvalidTransitionException($pef->status, Pef::STATUS_PENDING_ADVISING);
        }

        $pef->load('items.section.subject', 'student.user');
        $sectionIds = $pef->items->pluck('section_id')->toArray();

        $validator->validate($pef->student, $pef->term, $sectionIds);

        DB::transaction(function () use ($pef, $actor, $sectionIds) {
            foreach ($sectionIds as $id) {
                Section::where('id', $id)
                    ->lockForUpdate()
                    ->whereRaw('enrolled_count < capacity')
                    ->increment('enrolled_count');
            }

            $totalUnits = $pef->items->sum(fn($item) => $item->section->subject->units ?? 0);

            $pef->update([
                'status'      => Pef::STATUS_PENDING_ADVISING,
                'total_units' => $totalUnits,
                'remarks'     => null,
            ]);

            $this->logApproval($pef, 'student', 'submitted', null, $actor->id);
        });

        $adviser = $pef->student->adviser;
        if ($adviser) {
            $adviser->notify(new PefStatusChanged(
                $pef,
                "A PEF from {$pef->student->user->name} is awaiting your review."
            ));
        }
    }

    public function approve(Pef $pef, User $actor, ?string $remarks = null): void
    {
        $this->assertActorCanAct($pef, $actor);

        $currentStage = $this->stageFromStatus($pef->status);
        $nextStatus   = $this->nextStatus($pef->status);

        DB::transaction(function () use ($pef, $actor, $currentStage, $nextStatus, $remarks) {
            $pef->update(['status' => $nextStatus]);
            $this->logApproval($pef, $currentStage, 'approved', $remarks, $actor->id);
        });

        if ($nextStatus === Pef::STATUS_ENROLLED) {
            $pef->student->user->notify(new PefStatusChanged(
                $pef,
                'Congratulations! You are now officially enrolled. You may now view your COR.'
            ));
        } elseif ($nextStatus === Pef::STATUS_PENDING_CASHIER) {
            $pef->student->user->notify(new PefStatusChanged(
                $pef,
                'Your PEF has been validated by the Registrar. Please proceed to the Cashier for payment.'
            ));
        } else {
            $pef->student->user->notify(new PefStatusChanged(
                $pef,
                "Your PEF has been approved at the {$currentStage} stage and forwarded."
            ));
        }
    }

    public function returnToStudent(Pef $pef, User $actor, string $remarks): void
    {
        $this->assertActorCanAct($pef, $actor);

        $currentStage = $this->stageFromStatus($pef->status);

        DB::transaction(function () use ($pef, $actor, $currentStage, $remarks) {
            $this->releaseSeats($pef);
            $pef->update([
                'status'  => Pef::STATUS_RETURNED,
                'remarks' => $remarks,
            ]);
            $this->logApproval($pef, $currentStage, 'returned', $remarks, $actor->id);
        });

        $pef->student->user->notify(new PefStatusChanged(
            $pef,
            "Your PEF was returned with remarks. Please review and resubmit."
        ));
    }

    public function reject(Pef $pef, User $actor, string $remarks): void
    {
        $this->assertActorCanAct($pef, $actor);

        $currentStage = $this->stageFromStatus($pef->status);

        DB::transaction(function () use ($pef, $actor, $currentStage, $remarks) {
            $this->releaseSeats($pef);
            $pef->update([
                'status'  => Pef::STATUS_REJECTED,
                'remarks' => $remarks,
            ]);
            $this->logApproval($pef, $currentStage, 'rejected', $remarks, $actor->id);
        });

        $pef->student->user->notify(new PefStatusChanged(
            $pef,
            "Your PEF has been rejected. Reason: {$remarks}"
        ));
    }

    public function cancel(Pef $pef, User $actor): void
    {
        if (in_array($pef->status, Pef::TERMINAL_STATUSES, true)) {
            throw new InvalidTransitionException($pef->status, Pef::STATUS_CANCELLED);
        }

        DB::transaction(function () use ($pef, $actor) {
            $this->releaseSeats($pef);
            $pef->update(['status' => Pef::STATUS_CANCELLED]);
            $this->logApproval($pef, 'student', 'cancelled', null, $actor->id);
        });
    }

    private function assertActorCanAct(Pef $pef, User $actor): void
    {
        if ($actor->isAdmin()) {
            return;
        }

        $expected = self::STAGE_ROLE_MAP[$pef->status] ?? null;

        if ($expected === null || $actor->role !== $expected) {
            throw new InvalidTransitionException($pef->status, 'next');
        }
    }

    private function nextStatus(string $current): string
    {
        $flow = Pef::STATUS_FLOW;
        $idx  = array_search($current, $flow, true);

        if ($idx === false || $idx + 1 >= count($flow)) {
            throw new InvalidTransitionException($current, 'next');
        }

        return $flow[$idx + 1];
    }

    private function stageFromStatus(string $status): string
    {
        return str_replace('pending_', '', $status);
    }

    private function logApproval(
        Pef $pef,
        string $stage,
        string $action,
        ?string $remarks,
        int $actedBy
    ): void {
        PefApproval::create([
            'pef_id'   => $pef->id,
            'stage'    => $stage,
            'action'   => $action,
            'remarks'  => $remarks,
            'acted_by' => $actedBy,
            'acted_at' => now(),
        ]);
    }

    private function releaseSeats(Pef $pef): void
    {
        $submitted = [
            Pef::STATUS_PENDING_ADVISING,
            Pef::STATUS_PENDING_ADMISSION,
            Pef::STATUS_PENDING_REGISTRAR,
            Pef::STATUS_PENDING_CASHIER,
        ];

        if (! in_array($pef->status, $submitted, true)) {
            return;
        }

        $pef->load('items');
        foreach ($pef->items as $item) {
            Section::where('id', $item->section_id)
                ->where('enrolled_count', '>', 0)
                ->decrement('enrolled_count');
        }
    }
}
