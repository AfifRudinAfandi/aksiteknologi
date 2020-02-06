<?php
namespace App\Helpers;

use App;
use App\Models\Company;
use App\Models\Setting;
use App\Models\SocialLink;
use Illuminate\Support\Facades\Config;

class SettingHelper
{

    public static function companyProfiles() {
        return Company::where('profile_id', '=', ProfileHelper::getIdActive())->first();
    }

    public static function socialLinks() {
        return SocialLink::where('profile_id', '=', ProfileHelper::getIdActive())->first();
    }

    public static function generalSettings() {
        return Setting::where('profile_id', '=', ProfileHelper::getIdActive())->first();
    }

    public static function siteTitle() {
        $setting_title = !empty(self::generalSettings()->site_title) ? self::generalSettings()->site_title : '';
        $profile_title = !empty(self::companyProfiles()->name) ? self::companyProfiles()->name : '';
        if(!empty($setting_title) && !empty($profile_title)) {
            $title = $setting_title;
        } else if(!empty($setting_title) && empty($profile_title)) {
            $title = $setting_title;
        } else if(empty($setting_title) && !empty($profile_title)) {
            $title = $profile_title;
        } else {
            $title = Config::get('app.title');
        }
        return $title;
    }

    public static function companyName() {
        return !empty(self::companyProfiles()->company_name) ? strtoupper(self::companyProfiles()->company_name) : '';
    }

    public static function siteLogo() {
        if(method_exists(self::generalSettings(), 'getFirstMediaUrl') && !empty(self::generalSettings()->getFirstMediaUrl('images'))) {
            return self::generalSettings()->getFirstMediaUrl('images');
        } else if(method_exists(self::companyProfiles(), 'getFirstMediaUrl') && !empty(self::companyProfiles()->getFirstMediaUrl('images'))) {
            return self::companyProfiles()->getFirstMediaUrl('images');
        } else {
            return null;
        }

    }

    public static function siteFavicon() {
        if(method_exists(self::generalSettings(), 'getFirstMediaUrl')){
            $favicon = !empty(self::generalSettings()->getFirstMediaUrl('favicon', 'favicon')) ? self::generalSettings()->getFirstMediaUrl('favicon', 'favicon') : null;
            return $favicon;
        } else {
            return null;
        }
    }

    public static function serviceDesc() {
        return self::locale(self::generalSettings()->site_service_desc, self::generalSettings()->site_en_service_desc);
    }

    public static function partnerDesc() {
        return self::locale(self::generalSettings()->site_partner_desc, self::generalSettings()->site_en_partner_desc);
    }

    public static function siteLang() {
        return !empty(self::generalSettings()->lang) ? self::generalSettings()->lang : Config::get('app.locale');
    }

    public static function analyticViewId() {
        return !empty(self::generalSettings()->analytic_view_id) ? self::generalSettings()->analytic_view_id : null;
    }

    public static function gaScripts() {
        return !empty(self::generalSettings()->ga_scripts) ? self::generalSettings()->ga_scripts : null;
    }

    public static function name() {
        return !empty(self::companyProfiles()->name) ? self::companyProfiles()->name : null;
    }

    public static function address() {
        return !empty(self::companyProfiles()->address) ? self::companyProfiles()->address : null;
    }

    public static function vision() {
        return self::locale(self::companyProfiles()->vision, self::companyProfiles()->en_vision);
    }

    public static function about() {
        return self::locale(self::companyProfiles()->about_us, self::companyProfiles()->en_about_us);
    }

    public static function map() {
        return !empty(self::companyProfiles()->map) ? self::companyProfiles()->map : null;
    }

    public static function phone() {
        return !empty(self::companyProfiles()->phone) ? self::companyProfiles()->phone : null;
    }

    public static function email() {
        return !empty(self::companyProfiles()->email) ? self::companyProfiles()->email : null;
    }

    public static function locale($id, $en)
    {
        if(App::getLocale() == 'id'){
            return $id;
        }else if(App::getLocale() == 'en'){
            return $en;
        }else{
            return null;
        }
    }

}
