<?php

namespace App\Http\Controllers;

use App;
use App\Helpers\ProfileHelper;
use App\Models\Product;
use App\Models\Message;
use App\Models\Applicant;
use App\Models\Category;
use App\Models\Career;
use App\Models\Client;
use App\Models\Post;
use App\Models\Team;
use App\Models\Service;
use App\Models\Testimony;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JsValidator;
use Alert;

class HomeController extends Controller
{

    public function index()
    {
        $partners = Client::where([
            ['profile_id', '=', ProfileHelper::getIdActive()],
            ['is_displayed', '!=', 0],
        ])->orderBy('created_at', 'desc')->limit(5)->get();

        $services = Service::where([
            ['profile_id', '=', ProfileHelper::getIdActive()],
            ['is_displayed', '!=', 0],
        ])->orderBy('created_at', 'desc')->limit(5)->get();

        $mediaPosts = Post::where([
            ['status', '=', 'featured'],
            ['status', '!=', 'draft']
        ])->orderBy('created_at', 'desc')->limit(2)->get();

        $recentPosts = Post::where('status', '!=', 'draft')->limit(3)->orderBy('created_at', 'desc')->get();

        $aksiProducts = Product::where([
            ['profile_id', '=', ProfileHelper::getIdActive()],
            ['is_displayed', '!=', 0],
            ['type', '=', 'aksi']
        ])->orderBy('created_at', 'asc')->get();

        $ayoProducts = Product::where([
            ['profile_id', '=', ProfileHelper::getIdActive()],
            ['is_displayed', '!=', 0],
            ['type', '=', 'ayo']
        ])->orderBy('created_at', 'asc')->get();

        $testimonies = Testimony::where([
            ['profile_id', '=', ProfileHelper::getIdActive()],
            ['is_displayed', '!=', 0]
        ])->orderBy('created_at', 'desc')->limit(3)->get();

        return view('home')
            ->withAksiProducts($aksiProducts)
            ->withAyoProducts($ayoProducts)
            ->withRecentPosts($recentPosts)
            ->withMediaPosts($mediaPosts)
            ->withServices($services)
            ->withPartners($partners)
            ->withTestimonies($testimonies);
    }

    public function about()
    {
        $missions = DB::table('missions')->where([
            ['lang', '=', App::getLocale()],
            ['profile_id', '=', ProfileHelper::getIdActive()]
        ])->get();
        return view('about')
            ->withMissions($missions);
    }

    public function team()
    {
        $directors = Team::where('profile_id', '=', ProfileHelper::getIdActive())
            ->whereHas('division', function($q){
                $q->where('name', 'LIKE', '%direktur%');
            })->orderBy('id', 'asc')->get();

        $teams = Team::where('profile_id', '=', ProfileHelper::getIdActive())
            ->whereHas('division', function($q){
                $q->where('name', 'NOT LIKE', '%direktur%');
            })->orderBy('name', 'asc')->get();

            return view('team')
            ->withDirectors($directors)
            ->withTeams($teams);
    }

    public function partner()
    {
        $partners = Client::where([
            ['is_displayed', '!=', 0],
            ['profile_id', '=', ProfileHelper::getIdActive()]
        ])->orderBy('created_at', 'desc')->get();
        return view('partner')
        ->withPartners($partners);
    }

    public function product()
    {
        $aksiProducts = Product::where([
            ['profile_id', '=', ProfileHelper::getIdActive()],
            ['is_displayed', '!=', 0],
            ['type', '=', 'aksi']
        ])->orderBy('created_at', 'asc')->get();

        $ayoProducts = Product::where([
            ['profile_id', '=', ProfileHelper::getIdActive()],
            ['is_displayed', '!=', 0],
            ['type', '=', 'ayo']
        ])->orderBy('created_at', 'asc')->get();
        return view('product')
            ->withAksiProducts($aksiProducts)
            ->withAyoProducts($ayoProducts);
    }

    public function blog()
    {
        return view('blog')
            ->withCategories(Category::where('category_for', '=', 'blog')->get())
            ->withPosts(Post::where('status', '!=', 'draft')->orderBy('created_at', 'desc')->get());
    }

    public function blogCategory($category_id)
    {
        return view('blog')
            ->withCategories(Category::where('category_for', '=', 'blog')->get())
            ->withPosts(Post::where([['status', '!=', 'draft'],['category_id', '=', $category_id]])->orderBy('created_at', 'desc')->get());
    }

    public function single($slug)
    {
        $post = Post::where([
            ['status', '!=', 'draft'],
            ['slug', '=', $slug],
        ])->firstOrFail();

        $recentPosts = Post::where([
            ['status', '!=', 'draft'],
            ['slug', '!=', $slug],
        ])->limit(3)->orderBy('created_at', 'desc')->get();

        $tags = explode(',', $post->tag);
        $relatedPosts = Post::where([
            ['status', '!=', 'draft'],
            ['slug', '!=', $slug],
        ])->where(function($q) use($tags){
            foreach ($tags as $tag) {
                $q->orWhere('tag', 'LIKE', '%'.$tag.'%');
            }
        })->limit(5)->get();

        return view('single-blog')
            ->withRecentPosts($recentPosts)
            ->withRelatedPosts($relatedPosts)
            ->withPost($post);
    }

    public function career()
    {
        $allCareers = Career::all();
        $careerCategory = Category::where('category_for', '=', 'career')->get();
        foreach($careerCategory as $i => $category){
            $careers[$i] = [
                'category' => $category->category,
                'id' => 'id'.$category->id,
                'data' => Career::where('category_id', '=', $category->id)->get(),
            ];
        }
        return view('career')
            ->withCareers($careers)
            ->withAllCareers($allCareers);
    }

    public function careerApply($id)
    {
        $rule = [
            'position' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:10',
            'link' => 'required'
        ];

        $message = [
            'required' => ':attribute tidak boleh kosong',
            'email' => 'format email salah',
            'min' => ':attribute minimal :min karakter'
        ];

        $attribute = [
            'position' => 'Posisi',
            'name' => 'Nama',
            'email' => 'E-Mail',
            'phone' => 'Nomor telepon',
            'link' => 'Dokumen',
        ];

        return view('career-apply')
            ->withCareer(Career::where('id', '=', $id)->firstOrFail())
            ->withValidator(JsValidator::make($rule, $message, $attribute));
    }

    public function apply(Request $request)
    {
        $applicant = new Applicant();

        $applicant->career_id = $request->career_id;
        $applicant->name = $request->name;
        $applicant->email = $request->email;
        $applicant->phone = $request->phone;
        $applicant->document_link = $request->link;

        $save = $applicant->save();

        if($save){
            Alert::toast('Sukses mengirim data', 'success');
        }else{
            Alert::toast('Terjadi kesalahan', 'error');
        }
        return redirect()->route('app.career');
    }

    public function contact()
    {
        $rule = [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:10',
            'messages' => 'required'
        ];

        $message = [
            'required' => ':attribute tidak boleh kosong',
            'email' => 'format email salah',
            'min' => ':attribute minimal :min karakter'
        ];

        $attribute = [
            'name' => 'Nama',
            'email' => 'E-Mail',
            'phone' => 'Nomor telepon',
            'messages' => 'Pesan',
        ];

        return view('contact')
            ->withValidator(JsValidator::make($rule, $message, $attribute));
    }

    public function send(Request $request)
    {
        $messages = new Message();

        $messages->name = $request->name;
        $messages->email = $request->email;
        $messages->phone = $request->phone;
        $messages->messages = $request->messages;

        $save = $messages->save();

        if($save){
            Alert::toast('Terimaksih, pesan anda sudah kami terima!', 'success');
        }else{
            Alert::toast('Maaf, Terjadi kesalahan!', 'error');
        }
        return redirect()->route('app.contact');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        if(!empty($query)){
            $careers = Career::where('job_title', 'LIKE', '%'.$query.'%')->orderBy('created_at', 'desc')->get();
            $posts = Post::where('title', 'LIKE', '%'.$query.'%')
                ->where('status', '=', 'published')
                ->orWhere('content', 'LIKE', '%$query%')
                ->orderBy('created_at', 'desc')->get();
        } else {
            $careers = [];
            $posts = [];
        }
        return view('search-result')
            ->withCareers($careers)
            ->withPosts($posts)
            ->withQuery($query)
            ->withCount((int)count($posts)+count($careers));
    }

}
