<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function requirement()
    {
        return $this->hasMany('App\Models\Requirement', 'uuid', 'requirement_uuid');
    }

    public function applicant()
    {
        return $this->hasMany('App\Models\Applicant', 'career_id', 'id')->orderBy('created_at', 'desc');
    }

    public function basicRequirement()
    {
        return $this->requirement()->where('type', '=', 'basic');
    }

    public function specificRequirement()
    {
        return $this->requirement()->where('type', '=', 'specific');
    }

}
