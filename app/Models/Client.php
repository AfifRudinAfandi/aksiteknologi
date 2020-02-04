<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Client extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $fillable = [
        'name', 'description'
    ];

    // public function registerMediaConversions(Media $media = null)
    // {
    //     $this->addMediaConversion('thumb')
    //         ->width(200)
    //         ->height(200)
    //         ->sharpen(10);

    //     $this->addMediaConversion('square')
    //         ->fit(Manipulations::FIT_CROP, 412, 412)
    //         ->width(412)
    //         ->height(412)
    //         ->sharpen(10);
    // }

}
