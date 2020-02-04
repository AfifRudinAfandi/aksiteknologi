<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\ProfileHelper;
use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Team;
use DataTables;
use JsValidator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class TeamController extends Controller
{

    protected $profile_id;
    protected $rule;
    protected $message;
    protected $attribute;

    public function __construct()
    {
        $this->profile_id = ProfileHelper::getId();

        $this->rule = [
            'name' => 'required',
            'division_id' => 'required',
        ];

        $this->message = [
            'required' => ':attribute tidak boleh kosong',
            'min' => ':attribute harus minimal :min karakter',
            'email.email' => 'Format email salah',
        ];

        $this->attribute = [
            'name' => 'Nama',
            'division_id' => 'Divisi',
            'social_provider' => 'Sosial Media',
            'social_link' => 'Link Sosial Media',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.team.index');
    }

    public function divisionIndex()
    {
        return Division::all();
    }

    public function teamDivision($id)
    {
        $division_name = Division::where('id', '=', $id)->pluck('name')->first();
        return view('admin.team.index')
                ->withDivisionName($division_name)
                ->withDivisionId($id);
    }

    public function datatable(Request $request)
    {
        $division_id = $request->division_id;

        if(isset($division_id) && $division_id != null) {
            $teams = Team::where([
                ['division_id', '=', $division_id],
                ['profile_id', '=', $this->profile_id]
            ])->get();
        } else {
            $teams = Team::where('profile_id', '=', $this->profile_id)->get();
        }

        return DataTables::of($teams)
            ->addColumn('checkboxes', 'admin.partial.checkbox')
            ->editColumn('name', function ($team) {
                if(empty($team->getFirstMediaUrl('images'))) {
                    $image = asset('/stisla/img/avatar/avatar-5.png');
                } else {
                    $image = $team->getFirstMediaUrl('images', 'square');
                }
                return '<section>
                            <a data-fancybox="gallery" href='. $image .' class="text-decoration-none">
                                <img alt="image" src="' . $image . '" class="rounded-circle" width="35" style="vertical-align: top;">
                            </a>
                            <div class="d-inline-block ml-1">
                                <span class="m-0 p-0">'.$team->name.'</span><br>
                                <span class="m-0 p-0">'.$team->email.'</span>
                            </div>
                        </section>';
            })
            ->editColumn('division', function ($team) {
                $division = isset($team->division->name) ? $team->division->name : '';
                return $division;
            })
            ->editColumn('social', function ($team) {
                if (!empty($team->social_provider) && !empty($team->social_link)) {
                    return '<b>' . ucfirst(trans($team->social_provider)) . '</b>: ' . $team->social_link;
                } else {
                    return '-';
                }
            })
            ->addColumn('actions', 'admin.team.actions')
            ->rawColumns([
                'checkboxes' => 'checkboxes',
                'name' => 'name',
                'division' => 'division',
                'social' => 'social',
                'actions' => 'actions'
            ])
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $validator = JsValidator::make($this->rule, $this->message, $this->attribute);

        return view('admin.team.create')
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
        $team = new Team($request->except(['image']));
        $team->profile_id = ProfileHelper::getId();
        $team->name = $request->name;
        $team->division_id = $request->division_id;
        $team->email = $request->email;
        $team->bio = $request->bio;
        $team->social_provider = $request->social_provider;
        $team->social_link = $request->social_link;

        $save = $team->save();
           
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $team->addMediaFromRequest('image')->toMediaCollection('images');
        }

        if ($save) {
            Alert::toast('Sukses menyimpan data', 'success');
        } else {
            Alert::toast('Terjadi kesalahan!', 'error');
        }
        return redirect()->back();
    }


    public function divisionStore(Request $request)
    {
        $division = new Division();
        $division->name = $request->name;

        $save = $division->save();

        if ($save) {
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
        $validator = JsValidator::make($this->rule, $this->message, $this->attribute);

        $team = Team::where('id', '=', $id)->firstOrFail();
        $division = Division::all();
        return view('admin.team.edit')
            ->withDivisions($division)
            ->withTeam($team)
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
        $team = Team::findOrFail($id);

        $team->name = $request->name;
        $team->division_id = $request->division_id;
        $team->email = $request->email;
        $team->bio = $request->bio;
        $team->social_provider = $request->social_provider;
        $team->social_link = $request->social_link;

        $save = $team->save();

        if ($save) {

            if($request->hasFile('image') && $request->file('image')->isValid()){
                $team->clearMediaCollection('images');
                $team->addMediaFromRequest('image')->toMediaCollection('images');
            }
    
            Alert::toast('Sukses mengubah data', 'success');
        } else {
            Alert::toast('Terjadi kesalahan!', 'error');
        }
        return redirect()->back();
    }


    public function divisionUpdate(Request $request)
    {
        $division = Division::findOrFail($request->id);
        $division->name = $request->name;

        $save = $division->save();

        if ($save) {
            $output = [
                'status' => 'success',
                'message' => 'Berhasil mengubah divisi.'
            ];
        } else {
            $output = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan!'
            ];
        }
        return $output;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $team = Team::findOrFail($request->id);
            $delete = $team->delete();
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
            $team = Team::whereIn('id', $ids);
            $delete = $team->delete();
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


    public function divisionDestroy(Request $request)
    {
        $division = Division::findOrFail($request->id);
        $delete = $division->delete();
        if ($delete) {
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
