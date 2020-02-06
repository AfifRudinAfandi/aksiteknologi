<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Charts\VisitorChart;
use JsValidator;
use Alert;
use Analytics;
use App\Helpers\ProfileHelper;
use App\Helpers\SettingHelper;
use Spatie\Analytics\Period;
use App\Models\Message;
use App\Models\Company;
use App\Models\Post;
use App\Models\Setting;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $latestPosts = Post::latest()->limit(5)->get();
        $messages = Message::orderBy('created_at', 'desc')->limit(5)->get();

        $labels = collect([]);
        $visitors = collect([]);
        $chart = new VisitorChart;

        $analytic_view_id =  SettingHelper::analyticViewId();
        if(!empty($analytic_view_id)) {
            config(['analytics.view_id' => $analytic_view_id]);
        }

        try {
            $yearlyData = Analytics::performQuery(
                Period::years(1),
                'ga:sessions',
                [
                    'metrics' => 'ga:sessions, ga:pageviews',
                    'dimensions' => 'ga:yearMonth'
                ]
            );

            if(count($yearlyData) > 0) {
                foreach($yearlyData->rows as $row) {
                    $labels->push($row[0]);
                    $visitors->push(($row[1]));
                }
            }

            $chart->labels($labels);
            $chart->dataset('Total Pengunjung Perbulan', 'line', $visitors)
                ->lineTension(0)
                ->backgroundColor('#96a1f3')
                ->color('#6777ef');

        } catch (\Exception $e) {
            $error = json_decode($e->getMessage(), true);
            $error_msg = $error['error']['message'];
            Alert::toast('Analytic View Error: "'. $error_msg.'"', 'error');

            $chart->dataset('Total Pengunjung Perbulan', 'line', $visitors);
        }

        return view('admin.dashboard')
                ->withChart($chart)
                ->withLatestPost($latestPosts)
                ->withMessages($messages);
    }

    public function message()
    {
        return view('admin.message.index')
                ->withMessage(Message::latest()->first())
                ->withMessageLists(Message::orderBy('created_at', 'desc')->get());
    }

    public function showMessage($id)
    {
        return view('admin.message.index')
                ->withMessage(Message::where('id', '=', $id)->first())
                ->withMessageLists(Message::orderBy('created_at', 'desc')->get());
    }

    public function deleteMessage($id)
    {
        $msg = Message::where('id', '=', $id)->firstOrFail();
        if ($msg->delete()) {
            Alert::toast('Sukses menghapus pesan', 'success');
        } else {
            Alert::toast('Terjadi kesalahan!', 'error');
        }

        return redirect()->route('admin.message.index');

    }

    public function profile()
    {
        $profile = Company::where('profile_id', '=', ProfileHelper::getId())->first();

        return view('admin.setting.profile')
                ->withProfile($profile);
    }

    public function saveProfile(Request $request)
    {
        if(!isset($request->id) || empty($request->id)) {
            $company = new Company();
        }else{
            $company = Company::findOrFail($request->id);
        }

        $company->profile_id = ProfileHelper::getId();
        $company->name = $request->name;
        $company->company_name = $request->company_name;
        $company->address = $request->address;
        $company->about_us = $request->about_us;
        $company->en_about_us = $request->en_about_us;
        $company->email = $request->email;
        $company->phone = $request->phone;
        $company->vision = $request->vision;
        $company->en_vision = $request->en_vision;
        $company->map = $request->map;

        $company->save();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $company->clearMediaCollection('images');
            $company->addMediaFromRequest('image')->toMediaCollection('images');
        }

        if ($company) {
            Alert::toast('Sukses menyimpan data', 'success');
        } else {
            Alert::toast('Terjadi kesalahan!', 'error');
        }

        return redirect()->route('admin.setting.profile');
    }

    public function destroyProfileLogo($id) {
        $company = Company::where('id', '=', $id)->firstOrFail();
        $company->clearMediaCollection('images');
        return redirect()->back();
    }

    public function social()
    {
        return view('admin.setting.social')
                ->withSocial(SocialLink::where('profile_id', '=', ProfileHelper::getId())->first());
    }

    public function saveSocial(Request $request)
    {
        if(!isset($request->id) || empty($request->id)) {
            $social = new SocialLink();
        }else{
            $social = SocialLink::findOrFail($request->id);
        }

        $social->profile_id = ProfileHelper::getId();
        $social->facebook = $request->facebook;
        $social->instagram = $request->instagram;
        $social->linkedin = $request->linkedin;
        $social->youtube = $request->youtube;
        $social->twitter = $request->twitter;
        $social->google = $request->google;
        $social->dribbble = $request->dribbble;

        $social->save();

        if ($social) {
            Alert::toast('Sukses menyimpan data', 'success');
        } else {
            Alert::toast('Terjadi kesalahan!', 'error');
        }

        return redirect()->route('admin.setting.social');
    }

    public function web()
    {
        return view('admin.setting.web')
                ->withWeb(Setting::where('profile_id', '=', ProfileHelper::getId())->first());
    }

    public function saveWeb(Request $request)
    {
        if(!isset($request->id) || empty($request->id)) {
            $web = new Setting();
        }else{
            $web = Setting::findOrFail($request->id);
        }

        $web->profile_id = ProfileHelper::getId();
        $web->site_title = $request->site_title;
        $web->site_service_desc = $request->site_service_desc;
        $web->site_en_service_desc = $request->site_en_service_desc;
        $web->site_partner_desc = $request->site_partner_desc;
        $web->site_en_partner_desc = $request->site_en_partner_desc;
        $web->lang = $request->lang;
        $web->analytic_view_id = $request->analytic_view_id;
        $web->ga_scripts = $request->ga_scripts;

        $web->save();

        if ($web) {

            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                $web->clearMediaCollection('images');
                $web->addMediaFromRequest('logo')->toMediaCollection('images');
            }

            if ($request->hasFile('favicon') && $request->file('favicon')->isValid()) {
                $web->clearMediaCollection('favicon');
                $web->addMediaFromRequest('favicon')->toMediaCollection('favicon');
            }

            Alert::toast('Sukses menyimpan data', 'success');
        } else {
            Alert::toast('Terjadi kesalahan!', 'error');
        }

        return redirect()->route('admin.setting.web');
    }

    public function destroyWebLogo($id) {
        $web = Setting::where('id', '=', $id)->firstOrFail();
        $web->clearMediaCollection('images');
        return redirect()->back();
    }

    public function getMission()
    {
        return DB::table('missions')->where('profile_id', '=', ProfileHelper::getId())->get();
    }

    public function storeMission(Request $request)
    {
        $mission = DB::table('missions')->insert([
            'profile_id' => ProfileHelper::getId(),
            'content' => $request->content
        ]);

        if ($mission) {
            $output = [
                'status' => 'success',
                'message' => 'Berhasil menambah data.'
            ];
        } else {
            $output = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan!'
            ];
        }
        return $output;
    }

    public function updateMission(Request $request)
    {
        $mission = DB::table('missions')
                    ->where('id', '=', $request->id)
                    ->update(['content' => $request->content]);

        if ($mission) {
            $output = [
                'status' => 'success',
                'message' => 'Berhasil mengubah data.'
            ];
        } else {
            $output = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan!'
            ];
        }
        return $output;
    }

    public function destroyMission(Request $request)
    {
        $mission = DB::table('missions')->where('id', '=', $request->id)->delete();

        if ($mission) {
            $output = [
                'status' => 'success',
                'message' => 'Berhasil menghapus data.'
            ];
        } else {
            $output = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan!'
            ];
        }
        return $output;
    }

}
