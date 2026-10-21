<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_no',
        'program_id',
        'year_level',
        'adviser_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function adviser()
    {
        return $this->belongsTo(User::class, 'adviser_id');
    }

    public function pefs()
    {
        return $this->hasMany(Pef::class);
    }

    public function grades()
    {
        return $this->hasMany(StudentGrade::class);
    }

    public function activePef()
    {
        return $this->pefs()
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->latest()
            ->first();
    }
}
