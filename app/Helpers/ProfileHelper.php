<?php
namespace App\Helpers;

use Session;
use App\Models\Profile;

class ProfileHelper
{
    public static function getId() {
        return Profile::where('is_preview', '=', 1)->pluck('id')->first();
    }

    public static function getIdActive() {
        if(Session::has('profile-preview')){
            return Session::get('profile-preview');
        }else{
            return Profile::where('is_active', '=', 1)->pluck('id')->first();
        }
    }

    public static function getName() {
        return Profile::where('is_preview', '=', 1)->pluck('name')->first();
    }

    public static function getPreviewName() {
        return Profile::where('id', '=', Session::get('profile-preview'))->pluck('name')->first();
    }

    public static function getNameActive() {
        return Profile::where('is_active', '=', 1)->pluck('name')->first();
    }

}