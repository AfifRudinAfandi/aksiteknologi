<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\ProfileHelper;
use App\Http\Controllers\Controller;
use App\Models\Service;
use DataTables;
use JsValidator;
use Alert;
use Illuminate\Http\Request;

class ServiceController extends Controller
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
            'description' => 'required',
            'is_displayed' => 'required',
            'image' => 'required'
        ];

        $edit_rule = [
            'name' => 'required',
            'description' => 'required',
            'is_displayed' => 'required',
        ];

        $insert_validator = JsValidator::make($insert_rule, $this->message, $this->attribute);
        $edit_validator = JsValidator::make($edit_rule, $this->message, $this->attribute);

        return view('admin.service.index')
                ->withValidatorInsert($insert_validator)
                ->withValidatorEdit($edit_validator);
    }

    public function datatable(Request $request)
    {
        $services = Service::where('profile_id', '=', $this->profile_id)->get();
        return DataTables::of($services)
            ->addColumn('checkboxes', 'admin.partial.checkbox')
            ->editColumn('logo', function($service) {
                $image = $service->getFirstMediaUrl('images');
                return '<a data-fancybox="gallery" href='. $image .'>
                            <img alt="image" src="' . $image . '" width="50" style="vertical-align: top;">
                        </a>';
            })
            ->addColumn('actions', 'admin.service.actions')
            ->editColumn('is_displayed', function($service) {
                return ((int)$service->is_displayed > 0) ? '<span class="badge badge-primary">Ditampilkan</span>' : '<span class="badge badge-danger">Tidak</span>';
            })
            ->editColumn('created_at', function($service) {
                return $service->created_at->diffForHumans();
            })
            ->editColumn('updated_at', function($service) {
                return $service->updated_at->diffForHumans();
            })
            ->rawColumns([
                'checkboxes' => 'checkboxes',
                'logo' => 'logo',
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

            $service = new Service();

            $service->profile_id = $this->profile_id;
            $service->name = $request->name;
            $service->description = $request->description;
            $service->is_displayed = $request->is_displayed;

            $save = $service->save();
                    
            $service->addMediaFromRequest('image')->toMediaCollection('images');

            if ($save) {
                Alert::toast('Sukses menyimpan data', 'success');
            } else {
                Alert::toast('Terjadi kesalahan!', 'error');
            }

        } else {
            Alert::toast('Masukkan logo terlebih dahulu!', 'warning');
        }

        return redirect()->route('admin.service.index');
    }

    public function getEdit(Request $request)
    {
        return Service::where('id', '=', $request->id)->select(['id', 'name', 'description', 'is_displayed'])->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $service = Service::findOrFail($request->id);

        $service->profile_id = $this->profile_id;
        $service->name = $request->name;
        $service->description = $request->description;
        $service->is_displayed = $request->is_displayed;

        $save = $service->save();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {       
            $service->clearMediaCollection('images');
            $service->addMediaFromRequest('image')->toMediaCollection('images');
        }

        if ($save) {
            Alert::toast('Sukses mengubah data', 'success');
        } else {
            Alert::toast('Terjadi kesalahan!', 'error');
        }

        return redirect()->route('admin.service.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $service = Service::findOrFail($request->id);
            $delete = $service->delete();
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
            $service = Service::whereIn('id', $ids);
            $delete = $service->delete();
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
