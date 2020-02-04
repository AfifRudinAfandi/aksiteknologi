<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Post extends Model implements HasMedia
{

    use HasMediaTrait;

    protected $fillable = [
        'title', 'content', 'tag'
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('full')
            ->width(800)
            ->height(500)
            ->sharpen(10);

        $this->addMediaConversion('thumbnail')
            ->fit(Manipulations::FIT_CROP, 500, 330)
            ->width(500)
            ->height(330)
            ->sharpen(10);
    }

    public function author()
    {
        return $this->belongsTo('App\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\PostType', 'post_type_id', 'id');
    }

}
