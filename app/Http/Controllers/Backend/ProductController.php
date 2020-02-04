<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\ProfileHelper;
use App\Http\Controllers\Controller;
use App\Models\Product;
use DataTables;
use JsValidator;
use Alert;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    protected $profile_id;
    protected $message;
    protected $attribute;

    public function __construct()
    {
        $this->profile_id = ProfileHelper::getId();

        $this->message = [
            'required' => ':attribute tidak boleh kosong',
            'min' => ':attribute harus minimal :min karakter',
        ];

        $this->attribute = [
            'name' => 'Nama',
            'description' => 'Deskripsi',
            'type' => 'Tipe produk',
            'is_displayed' => 'Pilihan tampilkan',
            'image' => 'Logo',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $insert_rule = [
            'name' => 'required',
            'description' => 'required',
            'type' => 'required',
            'is_displayed' => 'required',
            'image' => 'required',
        ];

        $edit_rule = [
            'name' => 'required',
            'description' => 'required',
            'type' => 'required',
            'is_displayed' => 'required'
        ];

        $insert_validator = JsValidator::make($insert_rule, $this->message, $this->attribute);
        $edit_validator = JsValidator::make($edit_rule, $this->message, $this->attribute);

        return view('admin.product.index')
                ->withValidatorInsert($insert_validator)
                ->withValidatorEdit($edit_validator);
    }

    public function datatable(Request $request)
    {
        $products = Product::where('profile_id', '=', $this->profile_id)->get();
        return DataTables::of($products)
            ->addColumn('checkboxes', 'admin.partial.checkbox')
            ->editColumn('logo', function ($product) {
                $image = $product->getFirstMediaUrl('images');
                return '<a data-fancybox="gallery" href='. $image .'>
                            <img alt="image" src="' . $image . '" width="50" style="vertical-align: top;">
                        </a>';
            })
            ->addColumn('actions', 'admin.product.actions')
            ->editColumn('is_displayed', function($product) {
                return ((int)$product->is_displayed > 0) ? '<span class="badge badge-primary">Ditampilkan</span>' : '<span class="badge badge-danger">Tidak</span>';
            })
            ->editColumn('type', function($product) {
                return ucfirst(trans($product->type));
            })
            ->editColumn('created_at', function($product) {
                return [
                    'display' => $product->created_at->diffForHumans(),
                    'timestamp' => $product->created_at,
                ];
            })
            ->rawColumns([
                'checkboxes' => 'checkboxes',
                'is_displayed' => 'is_displayed',
                'logo' => 'logo',
                'actions' => 'actions'
            ])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $product = new Product();

            $product->profile_id = $this->profile_id;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->is_displayed = $request->is_displayed;
            $product->type = $request->type;

            $save = $product->save();
                    
            $product->addMediaFromRequest('image')->toMediaCollection('images');
            $product->addMediaFromRequest('thumb')->toMediaCollection('thumbs');

            if ($save) {
                Alert::toast('Sukses menyimpan data', 'success');
            } else {
                Alert::toast('Terjadi kesalahan!', 'error');
            }

        } else {
            Alert::toast('Masukkan logo terlebih dahulu!', 'warning');
        }

        return redirect()->route('admin.product.index');
    }

    public function getEdit(Request $request)
    {
        return Product::where('id', '=', $request->id)->select(['id', 'name', 'description', 'type', 'is_displayed'])->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $product->profile_id = $this->profile_id;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->is_displayed = $request->is_displayed;
        $product->type = $request->type;

        $save = $product->save();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {       
            $product->clearMediaCollection('images');
            $product->addMediaFromRequest('image')->toMediaCollection('images');
        }

        if ($request->hasFile('thumb') && $request->file('thumb')->isValid()) {       
            $product->clearMediaCollection('thumbs');
            $product->addMediaFromRequest('thumb')->toMediaCollection('thumbs');
        }

        if ($save) {
            Alert::toast('Sukses mengubah data', 'success');
        } else {
            Alert::toast('Terjadi kesalahan!', 'error');
        }

        return redirect()->route('admin.product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $product = Product::findOrFail($request->id);
            $delete = $product->delete();
            if ($delete) {
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
        if (isset($ids)) {
            $product = Product::whereIn('id', $ids);
            $delete = $product->delete();
            if ($delete) {
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

}
