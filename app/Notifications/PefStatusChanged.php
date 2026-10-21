<?php

namespace App\Notifications;

use App\Models\Pef;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PefStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        protected Pef $pef,
        protected string $message
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'pef_id'  => $this->pef->id,
            'status'  => $this->pef->status,
            'message' => $this->message,
            'url'     => route('student.pef.show', $this->pef),
        ];
    }
}
