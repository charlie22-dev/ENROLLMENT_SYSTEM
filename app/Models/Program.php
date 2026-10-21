<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = ['department_id', 'name', 'code'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function curriculumSubjects()
    {
        return $this->hasMany(CurriculumSubject::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'curriculum_subjects')
                    ->withPivot('year_level', 'term_no')
                    ->withTimestamps();
    }
}
