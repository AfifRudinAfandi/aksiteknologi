<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    public function job()
    {
        return $this->belongsTo('App\Models\Career', 'career_id', 'id');
    }
}
