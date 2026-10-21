<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PefItem extends Model
{
    protected $fillable = ['pef_id', 'section_id'];

    public function pef()
    {
        return $this->belongsTo(Pef::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
