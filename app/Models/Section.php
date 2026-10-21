<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'term_id',
        'section_code',
        'capacity',
        'enrolled_count',
        'instructor_id',
        'room_id',
    ];

    protected function casts(): array
    {
        return [
            'capacity'       => 'integer',
            'enrolled_count' => 'integer',
        ];
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function schedules()
    {
        return $this->hasMany(SectionSchedule::class);
    }

    public function pefItems()
    {
        return $this->hasMany(PefItem::class);
    }

    public function availableSlots(): int
    {
        return max(0, $this->capacity - $this->enrolled_count);
    }

    public function isFull(): bool
    {
        return $this->enrolled_count >= $this->capacity;
    }
}
