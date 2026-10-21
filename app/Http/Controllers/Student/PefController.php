<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePefRequest;
use App\Http\Requests\UpdatePefRequest;
use App\Models\Pef;
use App\Models\PefItem;
use App\Models\Section;
use App\Models\Term;
use App\Services\AssessmentService;
use App\Services\PefValidator;
use App\Services\PefWorkflow;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PefController extends Controller
{
    public function __construct(
        protected PefWorkflow $workflow,
        protected PefValidator $validator,
        protected AssessmentService $assessmentService,
    ) {}

    public function create(Request $request)
    {
        $student    = $request->user()->student;
        $activeTerm = Term::active()->first();

        abort_if(! $activeTerm, 404, 'No active enrollment term.');
        abort_if(! $activeTerm->isEnrollmentOpen(), 403, 'Enrollment period is not open.');

        $existingPef = $student->pefs()
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->where('term_id', $activeTerm->id)
            ->first();

        if ($existingPef) {
            return redirect()->route('student.pef.show', $existingPef)
                ->with('info', 'You already have an active PEF for this term.');
        }

        $sections = Section::with(['subject', 'schedules', 'instructor', 'room'])
            ->where('term_id', $activeTerm->id)
            ->get();

        return view('student.pef.create', compact('activeTerm', 'sections'));
    }

    public function store(StorePefRequest $request)
    {
        $student    = $request->user()->student;
        $activeTerm = Term::active()->first();

        abort_if(! $activeTerm, 404);

        $pef = Pef::create([
            'student_id'  => $student->id,
            'term_id'     => $activeTerm->id,
            'status'      => Pef::STATUS_DRAFT,
            'total_units' => 0,
        ]);

        $this->syncItems($pef, $request->section_ids);

        return redirect()->route('student.pef.show', $pef)
            ->with('success', 'PEF saved as draft. Review and submit when ready.');
    }

    public function show(Pef $pef)
    {
        $this->authorize('view', $pef);

        $pef->load([
            'items.section.subject',
            'items.section.schedules',
            'items.section.instructor',
            'items.section.room',
            'approvals.actor',
            'term',
            'student.user',
            'assessment',
            'payments.receiver',
        ]);

        return view('student.pef.show', compact('pef'));
    }

    public function edit(Pef $pef)
    {
        $this->authorize('update', $pef);

        $pef->load(['items.section.subject', 'term']);

        $sections = Section::with(['subject', 'schedules', 'instructor', 'room'])
            ->where('term_id', $pef->term_id)
            ->get();

        $selectedIds = $pef->items->pluck('section_id')->toArray();

        return view('student.pef.edit', compact('pef', 'sections', 'selectedIds'));
    }

    public function update(UpdatePefRequest $request, Pef $pef)
    {
        $this->authorize('update', $pef);

        $this->syncItems($pef, $request->section_ids);

        return redirect()->route('student.pef.show', $pef)
            ->with('success', 'PEF updated.');
    }

    public function submit(Pef $pef)
    {
        $this->authorize('submit', $pef);

        try {
            $this->workflow->submit($pef, auth()->user(), $this->validator);
            return redirect()->route('student.pef.show', $pef)
                ->with('success', 'PEF submitted successfully and is now pending advising.');
        } catch (\App\Exceptions\EnrollmentValidationException $e) {
            return redirect()->back()
                ->withErrors($e->getErrors())
                ->with('error', 'Your PEF could not be submitted. Please fix the errors below.');
        } catch (\App\Exceptions\InvalidTransitionException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function cancel(Pef $pef)
    {
        $this->authorize('cancel', $pef);

        $this->workflow->cancel($pef, auth()->user());

        return redirect()->route('student.dashboard')
            ->with('success', 'PEF has been cancelled.');
    }

    public function cor(Pef $pef)
    {
        $this->authorize('view', $pef);
        abort_if($pef->status !== Pef::STATUS_ENROLLED, 403, 'COR is only available after enrollment.');

        $pef->load([
            'items.section.subject',
            'items.section.schedules',
            'items.section.instructor',
            'items.section.room',
            'assessment',
            'payments',
            'student.user',
            'student.program.department',
            'term',
        ]);

        return view('student.cor', compact('pef'));
    }

    public function corPdf(Pef $pef)
    {
        $this->authorize('view', $pef);
        abort_if($pef->status !== Pef::STATUS_ENROLLED, 403);

        $pef->load([
            'items.section.subject',
            'items.section.schedules',
            'items.section.instructor',
            'items.section.room',
            'assessment',
            'payments',
            'student.user',
            'student.program.department',
            'term',
        ]);

        $pdf = Pdf::loadView('student.cor', compact('pef'));

        return $pdf->download("COR-{$pef->student->student_no}.pdf");
    }

    private function syncItems(Pef $pef, array $sectionIds): void
    {
        $pef->items()->delete();
        foreach ($sectionIds as $sectionId) {
            PefItem::create(['pef_id' => $pef->id, 'section_id' => $sectionId]);
        }

        $totalUnits = Section::with('subject')
            ->whereIn('id', $sectionIds)
            ->get()
            ->sum(fn($s) => $s->subject->units ?? 0);

        $pef->update(['total_units' => $totalUnits]);
    }
}
