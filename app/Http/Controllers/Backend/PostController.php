<?php

namespace App\Http\Controllers\Backend;

use App\Models\Post;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use JsValidator;
use Alert;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.post.index');
    }


    // public function postType($type)
    // {
    //     $default_type = ['berita', 'karir'];

    //     if(in_array($type, $default_type)){
    //         return view('admin.post.index')
    //                 ->withType($type);
    //     }else{
    //         return abort(404);
    //     }

    // }


    public function datatable(Request $request)
    {
        // if(isset($request->type)){
        //     $get_type_id = DB::table('post_type')->select('id')->where('name', '=', $request->type)->first();
        //     $posts = Post::where('post_type_id', '=', $get_type_id->id)->get();
        // } else {
        //     $posts = Post::all();
        // }

        $posts = Post::all();

        return DataTables::of($posts)
                ->addColumn('checkboxes', 'admin.partial.checkbox')
                ->editColumn('title', 'admin.post.title')
                ->editColumn('category', function($post) {
                    if(isset($post->category_id) && isset($post->category->category)) {
                        return $post->category->category;
                    } else {
                        return '-';
                    }
                })
                ->editColumn('author', function($post) {
                    return '<a href="#"><img alt="image" src="'.asset('/stisla/img/avatar/avatar-5.png').'" class="rounded-circle" width="35" data-toggle="title" title=""><div class="d-inline-block ml-1">'.$post->author->name.'</div></a>';
                })
                ->editColumn('view', '{{ $view }} x')
                ->editColumn('created_at', function($post) {
                    return $post->created_at->diffForHumans();
                })
                ->addColumn('status', function($post){
                    $status = $post->status;
                    $color = null;
                    if($status === 'draft'){
                        $color = 'danger';
                    }else if($status === 'published'){
                        $color = 'primary';
                    }else if($status === 'archived'){
                        $color = 'warning';
                    }
                    return '<span class="badge badge-'.$color.'">'.$post->status.'</span>';
                })
                ->rawColumns([
                    'title' => 'title',
                    'author' => 'author',
                    'checkboxes' => 'checkboxes',
                    'status' => 'status'
                ])->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rule = [
            // 'post_type_id' => 'required',
            'category_id' => 'required',
            'title' => 'required|min:5',
            'content' => 'required|min:20',
            'status' => 'required'
        ];

        $message = [
            'required' => ':attribute tidak boleh kosong',
            'min' => ':attribute harus minimal :min karakter'
        ];

        $attribute = [
            'title' => 'Judul',
            // 'post_type_id' => 'Tipe post',
            'category_id' => 'Kategori',
            'content' => 'Kontent',
            'status' => 'Status post',
        ];

        $validator = JsValidator::make($rule, $message, $attribute);

        // $type = DB::table('post_type')->select('id', 'name')->get();
        return view('admin.post.create')
                ->withValidator($validator);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = new Post();

        $post->title = $request->title;
        $post->category_id = $request->category_id;
        // $post->post_type_id = $request->post_type_id;
        $post->content = $request->content;
        $post->tag = $request->tag;
        $post->status = $request->status;
        $post->author_id = Auth::user()->id;
        $post->content_type = 'post';
        $post->slug = str_replace(' ','-',strtolower($request->title)).'-'.mt_rand(100000, 999999);

        $save = $post->save();

        if($request->hasFile('image') && $request->file('image')->isValid()){
            $post->addMediaFromRequest('image')->toMediaCollection('images');
        }

        if($save) {
            Alert::toast('Sukses menyimpan post', 'success');
        } else {
            Alert::toast('Terjadi kesalahan!', 'error');
        }
        return redirect()->back();        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rule = [
            // 'post_type_id' => 'required',
            'category_id' => 'required',
            'title' => 'required|min:5',
            'content' => 'required|min:20',
            'status' => 'required'
        ];

        $message = [
            'required' => ':attribute tidak boleh kosong',
            'min' => ':attribute harus minimal :min karakter'
        ];

        $attribute = [
            'title' => 'Judul',
            // 'post_type_id' => 'Tipe post',
            'category_id' => 'Kategori',
            'content' => 'Kontent',
            'status' => 'Status post',
        ];

        $validator = JsValidator::make($rule, $message, $attribute);

        $post = Post::where('id', $id)->firstOrFail();

        // $type = DB::table('post_type')->select('id', 'name')->get();
        return view('admin.post.edit')
                ->withPost($post)
                ->withValidator($validator);
        }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $post->title = $request->title;
        $post->category_id = $request->category_id;
        // $post->post_type_id = $request->post_type_id;
        $post->content = $request->content;
        $post->tag = $request->tag;
        $post->status = $request->status;
        $post->author_id = Auth::user()->id;
        $post->content_type = 'post';
        $post->slug = str_replace(' ','-',strtolower($request->title)).'-'.mt_rand(100000, 999999);

        $save = $post->save();

        if($request->hasFile('image') && $request->file('image')->isValid()){
            $post->clearMediaCollection('images');
            $post->addMediaFromRequest('image')->toMediaCollection('images');
        }

        if($save) {
            Alert::toast('Sukses mengubah post', 'success');
        } else {
            Alert::toast('Terjadi kesalahan!', 'error');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(isset($request->id)){
            $post = Post::findOrFail($request->id);
            $delete = $post->delete();
            if($delete) {
                $output = [
                    'status' => 'success',
                    'message' => 'Berhasil menghapus data.'
                ];
            } else {
                $output = [
                    'status' => 'error',
                    'message' => 'Gagal menghapus data.'
                ];
            }
        } else {
            $output = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan!'
            ];
        }

        return $output;
    }


    public function destroyAll(Request $request)
    {
        $ids = $request->id;
        if(isset($ids)){
            $post = Post::whereIn('id', $ids);
            $delete = $post->delete();
            if($delete) {
                $output = [
                    'status' => 'success',
                    'message' => 'Berhasil menghapus data yang dipilih.'
                ];
            } else {
                $output = [
                    'status' => 'error',
                    'message' => 'Gagal menghapus data.'
                ];
            }
        } else {
            $output = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan!'
            ];
        }

        return $output;
    }


    public function getCategory()
    {
        return Category::where('category_for', '=', 'blog')->select('id', 'category')->get();
    }

    public function addCategory(Request $request)
    {
        $category = new Category();
        $category->category_for = 'blog';
        $category->category = $request->category;
        $add = $category->save();

        if($add) {
            $output = [
                'status' => 'success',
                'message' => 'Berhasil menambah kategori.'
            ];
        } else {
            $output = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan!'
            ];
        }

        return $output;
    }

    public function destroyCategory(Request $request)
    {
        $delete = Category::findOrFail($request->id)->delete();

        if($delete) {
            $output = [
                'status' => 'success',
                'message' => 'Berhasil menghapus kategori.'
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
