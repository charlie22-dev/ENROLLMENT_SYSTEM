<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'term_id',
        'grade',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'grade' => 'decimal:2',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function isFlagged(): bool
    {
        return in_array($this->remarks, ['failed', 'inc', 'dropped'], true);
    }

    public function flagColor(): string
    {
        return match ($this->remarks) {
            'failed'  => 'red',
            'inc'     => 'yellow',
            'dropped' => 'gray',
            default   => 'green',
        };
    }
}
