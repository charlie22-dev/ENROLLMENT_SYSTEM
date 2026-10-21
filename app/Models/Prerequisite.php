<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prerequisite extends Model
{
    protected $fillable = ['subject_id', 'prerequisite_id'];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function prerequisiteSubject()
    {
        return $this->belongsTo(Subject::class, 'prerequisite_id');
    }
}
