<?php

namespace App\Policies;

use App\Models\Pef;
use App\Models\User;

class PefPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->isAdmin() ? true : null;
    }

    public function view(User $user, Pef $pef): bool
    {
        return match ($user->role) {
            'student'   => $pef->student->user_id === $user->id,
            'adviser'   => $pef->student->adviser_id === $user->id
                           && $pef->status === Pef::STATUS_PENDING_ADVISING,
            'admission' => $pef->status === Pef::STATUS_PENDING_ADMISSION,
            'registrar' => $pef->status === Pef::STATUS_PENDING_REGISTRAR,
            'cashier'   => $pef->status === Pef::STATUS_PENDING_CASHIER,
            default     => false,
        };
    }

    public function update(User $user, Pef $pef): bool
    {
        return $user->isStudent()
            && $pef->student->user_id === $user->id
            && $pef->isEditable();
    }

    public function submit(User $user, Pef $pef): bool
    {
        return $user->isStudent()
            && $pef->student->user_id === $user->id
            && $pef->isEditable();
    }

    public function approve(User $user, Pef $pef): bool
    {
        return $this->canActOnStage($user, $pef);
    }

    public function return(User $user, Pef $pef): bool
    {
        return $this->canActOnStage($user, $pef);
    }

    public function reject(User $user, Pef $pef): bool
    {
        return $user->isAdviser()
            && $pef->status === Pef::STATUS_PENDING_ADVISING
            && $pef->student->adviser_id === $user->id;
    }

    public function cancel(User $user, Pef $pef): bool
    {
        return $user->isStudent()
            && $pef->student->user_id === $user->id
            && $pef->isActive();
    }

    private function canActOnStage(User $user, Pef $pef): bool
    {
        return match ($user->role) {
            'adviser'   => $pef->status === Pef::STATUS_PENDING_ADVISING
                           && $pef->student->adviser_id === $user->id,
            'admission' => $pef->status === Pef::STATUS_PENDING_ADMISSION,
            'registrar' => $pef->status === Pef::STATUS_PENDING_REGISTRAR,
            'cashier'   => $pef->status === Pef::STATUS_PENDING_CASHIER,
            default     => false,
        };
    }
}
