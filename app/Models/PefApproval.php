<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PefApproval extends Model
{
    protected $fillable = [
        'pef_id',
        'stage',
        'action',
        'remarks',
        'acted_by',
        'acted_at',
    ];

    protected function casts(): array
    {
        return [
            'acted_at' => 'datetime',
        ];
    }

    public function pef()
    {
        return $this->belongsTo(Pef::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'acted_by');
    }

    public function actionLabel(): string
    {
        return match ($this->action) {
            'submitted'  => 'Submitted',
            'approved'   => 'Approved',
            'returned'   => 'Returned',
            'rejected'   => 'Rejected',
            'cancelled'  => 'Cancelled',
            default      => ucfirst($this->action),
        };
    }

    public function actionColor(): string
    {
        return match ($this->action) {
            'approved'  => 'green',
            'returned'  => 'yellow',
            'rejected'  => 'red',
            'cancelled' => 'red',
            default     => 'blue',
        };
    }
}
