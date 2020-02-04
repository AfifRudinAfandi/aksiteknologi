<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Team extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $guarded = [];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('full')
            ->width(800)
            ->height(500)
            ->sharpen(10);

        $this->addMediaConversion('square')
            ->fit(Manipulations::FIT_CROP, 412, 412)
            ->width(412)
            ->height(412)
            ->sharpen(10);
    }

    public function division()
    {
        return $this->belongsTo('App\Models\Division', 'division_id', 'id');
    }
}
