<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_year',
        'semester',
        'enrollment_start',
        'enrollment_end',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'enrollment_start' => 'date',
            'enrollment_end'   => 'date',
            'is_active'        => 'boolean',
        ];
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function pefs()
    {
        return $this->hasMany(Pef::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isEnrollmentOpen(): bool
    {
        $today = now()->toDateString();
        return $this->is_active
            && $today >= $this->enrollment_start->toDateString()
            && $today <= $this->enrollment_end->toDateString();
    }

    public function label(): string
    {
        return "{$this->school_year} — {$this->semester} Semester";
    }
}
