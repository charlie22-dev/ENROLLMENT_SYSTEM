<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'pef_id',
        'amount',
        'or_number',
        'received_by',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'amount'  => 'decimal:2',
        ];
    }

    public function pef()
    {
        return $this->belongsTo(Pef::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
