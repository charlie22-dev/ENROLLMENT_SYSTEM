<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = ['pef_id', 'tuition', 'misc_fees', 'total'];

    protected function casts(): array
    {
        return [
            'misc_fees' => 'array',
            'tuition'   => 'decimal:2',
            'total'     => 'decimal:2',
        ];
    }

    public function pef()
    {
        return $this->belongsTo(Pef::class);
    }
}
