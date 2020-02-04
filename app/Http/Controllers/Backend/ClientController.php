<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\ProfileHelper;
use App\Http\Controllers\Controller;
use App\Models\Client;
use DataTables;
use JsValidator;
use Alert;
use Illuminate\Http\Request;

class ClientController extends Controller
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
            'image' => 'Logo',
            'is_displayed' => 'Pilihan tampilkan'
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
            'image' => 'required',
            'is_displayed' => 'required'
        ];

        $edit_rule = [
            'name' => 'required',
            'description' => 'required',
            'id_displayed' => 'required'
        ];

        $insert_validator = JsValidator::make($insert_rule, $this->message, $this->attribute);
        $edit_validator = JsValidator::make($edit_rule, $this->message, $this->attribute);

        return view('admin.client.index')
                ->withValidatorInsert($insert_validator)
                ->withValidatorEdit($edit_validator);
    }

    public function datatable(Request $request)
    {
        $clients = Client::where('profile_id', '=', $this->profile_id)->get();
        return DataTables::of($clients)
            ->addColumn('checkboxes', 'admin.partial.checkbox')
            ->editColumn('logo', function ($client) {
                $image = $client->getFirstMediaUrl('images');
                return '<a data-fancybox="gallery" href='. $image .'>
                            <img alt="image" src="' . $image . '" width="50" style="vertical-align: top;">
                        </a>';
            })
            ->addColumn('actions', 'admin.client.actions')
            ->editColumn('is_displayed', function($client) {
                return ((int)$client->is_displayed > 0) ? '<span class="badge badge-primary">Ditampilkan</span>' : '<span class="badge badge-danger">Tidak</span>';
            })
            ->editColumn('created_at', function($client) {
                return $client->created_at->diffForHumans();
            })
            ->editColumn('updated_at', function($client) {
                return $client->updated_at->diffForHumans();
            })
            ->rawColumns([
                'checkboxes' => 'checkboxes',
                'is_displayed' => 'is_displayed',
                'logo' => 'logo',
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

            $client = new Client();

            $client->profile_id = $this->profile_id;
            $client->name = $request->name;
            $client->description = $request->description;
            $client->is_displayed = $request->is_displayed;

            $save = $client->save();
                    
            $client->addMediaFromRequest('image')->toMediaCollection('images');

            if ($save) {
                Alert::toast('Sukses menyimpan data', 'success');
            } else {
                Alert::toast('Terjadi kesalahan!', 'error');
            }

        } else {
            Alert::toast('Masukkan logo terlebih dahulu!', 'warning');
        }

        return redirect()->route('admin.client.index');
    }

    public function getEdit(Request $request)
    {
        return Client::where('id', '=', $request->id)->select(['id', 'name', 'description', 'is_displayed'])->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $client = Client::findOrFail($request->id);

        $client->profile_id = $this->profile_id;
        $client->name = $request->name;
        $client->description = $request->description;
        $client->is_displayed = $request->is_displayed;

        $save = $client->save();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {       
            $client->clearMediaCollection('images');
            $client->addMediaFromRequest('image')->toMediaCollection('images');
        }

        if ($save) {
            Alert::toast('Sukses mengubah data', 'success');
        } else {
            Alert::toast('Terjadi kesalahan!', 'error');
        }

        return redirect()->route('admin.client.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $client = Client::findOrFail($request->id);
            $delete = $client->delete();
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
            $client = Client::whereIn('id', $ids);
            $delete = $client->delete();
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
