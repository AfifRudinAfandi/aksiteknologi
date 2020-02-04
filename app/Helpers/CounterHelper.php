<?php
namespace App\Helpers;

use App\Models\Career;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

class CounterHelper
{

    public static function modelAll($class)
    {
        $model = new $class;
        $count = $model->where('profile_id', '=', ProfileHelper::getId())->count();
        return (int)$count;
    }

    public static function postAll()
    {
        return (int)Post::all()->count();
    }

    public static function careerAll()
    {
        return (int)Career::all()->count();
    }


    // public static function postType($type)
    // {
    //     return (int)Post::join('post_type as type', 'posts.post_type_id', '=', 'type.id')
    //                         ->where('name', '=', $type)
    //                         ->get()
    //                         ->count();
    // }

}