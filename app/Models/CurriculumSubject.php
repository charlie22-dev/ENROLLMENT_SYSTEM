<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurriculumSubject extends Model
{
    protected $fillable = ['program_id', 'subject_id', 'year_level', 'term_no'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
