<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'title', 'units', 'type'];

    public function prerequisites()
    {
        return $this->belongsToMany(
            Subject::class,
            'prerequisites',
            'subject_id',
            'prerequisite_id'
        );
    }

    public function requiredBy()
    {
        return $this->belongsToMany(
            Subject::class,
            'prerequisites',
            'prerequisite_id',
            'subject_id'
        );
    }

    public function curriculumSubjects()
    {
        return $this->hasMany(CurriculumSubject::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function grades()
    {
        return $this->hasMany(StudentGrade::class);
    }
}
