<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\ProfileHelper;
use App\Http\Controllers\Controller;
use App\Models\Service;
use DataTables;
use JsValidator;
use Alert;
use App\Models\Testimony;
use Illuminate\Http\Request;

class TestimonyController extends Controller
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
            'is_displayed' => 'Opsi tampilkan',
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
            'bio' => 'required',
            'content' => 'required',
            'is_displayed' => 'required',
            'image' => 'required'
        ];

        $edit_rule = [
            'name' => 'required',
            'bio' => 'Bio',
            'content' => 'Konten',
            'description' => 'required',
            'is_displayed' => 'required',
        ];

        $insert_validator = JsValidator::make($insert_rule, $this->message, $this->attribute);
        $edit_validator = JsValidator::make($edit_rule, $this->message, $this->attribute);

        return view('admin.testimony.index')
                ->withValidatorInsert($insert_validator)
                ->withValidatorEdit($edit_validator);
    }

    public function datatable(Request $request)
    {
        $testimonys = Testimony::where('profile_id', '=', $this->profile_id)->get();
        return DataTables::of($testimonys)
            ->addColumn('checkboxes', 'admin.partial.checkbox')
            ->editColumn('name', function ($testimony) {
                if(empty($testimony->getFirstMediaUrl('images'))) {
                    $image = asset('/stisla/img/avatar/avatar-5.png');
                } else {
                    $image = $testimony->getFirstMediaUrl('images', 'square');
                }
                return '<section>
                            <a data-fancybox="gallery" href='. $image .' class="text-decoration-none">
                                <img alt="image" src="' . $image . '" class="rounded-circle" width="35" style="vertical-align: top;">
                            </a>
                            <div class="d-inline-block ml-1">
                                <span class="m-0 p-0">'.$testimony->name.'</span><br>
                                <span class="m-0 p-0">'.$testimony->bio.'</span>
                            </div>
                        </section>';
            })
            ->addColumn('actions', 'admin.testimony.actions')
            ->editColumn('is_displayed', function($testimony) {
                return ((int)$testimony->is_displayed > 0) ? '<span class="badge badge-primary">Ditampilkan</span>' : '<span class="badge badge-danger">Tidak</span>';
            })
            ->editColumn('created_at', function($testimony) {
                return $testimony->created_at->diffForHumans();
            })
            ->editColumn('updated_at', function($testimony) {
                return $testimony->updated_at->diffForHumans();
            })
            ->rawColumns([
                'checkboxes' => 'checkboxes',
                'name' => 'name',
                'is_displayed' => 'is_displayed',
                'actions' => 'actions'
            ])
            ->make();
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

            $testimony = new Testimony();

            $testimony->profile_id = $this->profile_id;
            $testimony->name = $request->name;
            $testimony->bio = $request->bio;
            $testimony->content = $request->content;
            $testimony->is_displayed = $request->is_displayed;

            $save = $testimony->save();
                    
            $testimony->addMediaFromRequest('image')->toMediaCollection('images');

            if ($save) {
                Alert::toast('Sukses menyimpan data', 'success');
            } else {
                Alert::toast('Terjadi kesalahan!', 'error');
            }

        } else {
            Alert::toast('Masukkan logo terlebih dahulu!', 'warning');
        }

        return redirect()->route('admin.testimony.index');
    }

    public function getEdit(Request $request)
    {
        return Testimony::where('id', '=', $request->id)->select(['id', 'name', 'bio', 'content','is_displayed'])->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $testimony = Testimony::findOrFail($request->id);

        $testimony->profile_id = $this->profile_id;
        $testimony->name = $request->name;
        $testimony->bio = $request->bio;
        $testimony->content = $request->content;
        $testimony->is_displayed = $request->is_displayed;

        $save = $testimony->save();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {       
            $testimony->clearMediaCollection('images');
            $testimony->addMediaFromRequest('image')->toMediaCollection('images');
        }

        if ($save) {
            Alert::toast('Sukses mengubah data', 'success');
        } else {
            Alert::toast('Terjadi kesalahan!', 'error');
        }

        return redirect()->route('admin.testimony.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $testimony = Testimony::findOrFail($request->id);
            $delete = $testimony->delete();
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
            $testimony = Testimony::whereIn('id', $ids);
            $delete = $testimony->delete();
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
