<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Testimony extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $table = "testimony";

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('square')
            ->fit(Manipulations::FIT_CROP, 412, 412)
            ->width(412)
            ->height(412)
            ->sharpen(10);
    }

}
