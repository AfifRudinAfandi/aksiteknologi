<?php

namespace App\Http\Controllers\Backend;

use App\Models\Requirement;
use App\Models\Career;
use App\Models\Category;
use App\Models\Applicant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use JsValidator;
use Alert;

class CareerController extends Controller
{
    protected $rule;
    protected $message;
    protected $attribute;

    public function __construct()
    {
        $this->rule = [
            'category_id' => 'required',
            'job_title' => 'required|min:5',
            'basic' => 'required',
        ];

        $this->message = [
            'required' => ':attribute tidak boleh kosong',
            'min' => ':attribute harus minimal :min karakter'
        ];

        $this->attribute = [
            'job_title' => 'Judul',
            'category_id' => 'Kategori',
            'basic' => 'Basic Requirements'
        ];

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.career.index');
    }


    public function datatable(Request $request)
    {
        $careers = Career::all();

        return DataTables::of($careers)
                ->addColumn('checkboxes', 'admin.career.checkbox')
                ->editColumn('title', function($career) {
                    return '<a href="'.route('admin.career.show', $career->id).'">'.$career->job_title.'</a>';
                })
                ->editColumn('category', function($career) {
                    return isset($career->category_id) ? $career->category->category : '-';
                })
                ->editColumn('created_at', function($career) {
                    return $career->created_at->diffForHumans();
                })
                ->addColumn('applicant', function($career) {
                    return $career->applicant->count();
                })
                ->addColumn('action', 'admin.career.actions')
                ->rawColumns([
                    'checkboxes' => 'checkboxes',
                    'title' => 'title',
                    'action' => 'action',
                ])->make();
    }

    public function datatableApplicant(Request $request)
    {
        $applicant = Career::where('id', '=', $request->career_id)->firstOrFail()->applicant();
        return DataTables::of($applicant)
            ->editColumn('name', function($a) {
                return '
                    <h6>'.$a->name.'</h6>
                    <span>'.$a->email.'</span><br/>
                    <span>'.$a->phone.'</span>
                ';
            })
            ->editColumn('document_link', function($a) {
                return '<a href="'.$a->document_link.'" target="_blank">'.$a->document_link.'</a>';
            })
            ->editColumn('created_at', function($career) {
                return $career->created_at->diffForHumans();
            })
            ->rawColumns([
                'name' => 'name',
                'document_link' => 'document_link',
                'action' => 'action',
            ])->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $validator = JsValidator::make($this->rule, $this->message, $this->attribute);

        return view('admin.career.create')
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
        $basics = $request->basic;
        $specifics = $request->specific;

        if(!in_array(null, $basics, true)) {

            $uuid = uniqid();

            $career = new Career();

            $career->requirement_uuid = $uuid;
            $career->category_id = $request->category_id;
            $career->job_title = $request->job_title;
            $career->job_desc = $request->job_desc;

            $save = $career->save();

            if($save) {

                foreach($basics as $index => $basic){
                    if($basic != null) {
                        $data[$index] = [
                            'uuid' => $uuid,
                            'content' => $basic,
                            'type' => 'basic',
                        ];
                    }
                }

                Requirement::insert($data);

                if(!in_array(null, $specifics, true)) {
                    foreach($specifics as $index => $specific) {
                        if($specific != null) {
                            $dataSpecific[$index] = [
                                'uuid' => $uuid,
                                'content' => $specific,
                                'type' => 'specific',
                            ];
                        }
                    }
                    Requirement::insert($dataSpecific);
                }

                Alert::toast('Sukses menyimpan data', 'success');
            } else {
                Alert::toast('Terjadi kesalahan!', 'error');
            }
        } else {
            Alert::toast('Basic requirement harus diisi minimal satu', 'warning');
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
        return view('admin.career.show')
            ->withCareer(Career::where('id', '=', $id)->firstOrFail());
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

        $career = Career::where('id', $id)->firstOrFail();

        $category = Category::where('category_for', '=', 'career')->get();

        $basics = Requirement::where([
            ['uuid', '=', $career->requirement_uuid],
            ['type', '=', 'basic']
        ])->get();

        $specifics = Requirement::where([
            ['uuid', '=', $career->requirement_uuid],
            ['type', '=', 'specific']
        ])->get();

        return view('admin.career.edit')
                ->withCategory($category)
                ->withBasics($basics)
                ->withSpecifics($specifics)
                ->withCareer($career)
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
        $uuid = $request->uuid;
        $basics = $request->basic;
        $specifics = $request->specific;

        if(!in_array(null, $basics, true)) {
            $career = Career::findOrFail($id);

            $career->category_id = $request->category_id;
            $career->job_title = $request->job_title;
            $career->job_desc = $request->job_desc;

            $save = $career->save();

            if($save) {

                Requirement::where('uuid', '=', $request->uuid)->delete();

                foreach($basics as $index => $basic){
                    if($basic != null) {
                        $data[$index] = [
                            'uuid' => $uuid,
                            'content' => $basic,
                            'type' => 'basic',
                        ];
                    }
                }

                Requirement::insert($data);

                if(!in_array(null, $specifics, true)) {
                    foreach($specifics as $index => $specific) {
                        if($specific != null) {
                            $dataSpecific[$index] = [
                                'uuid' => $uuid,
                                'content' => $specific,
                                'type' => 'specific',
                            ];
                        }
                    }
                    if(isset($dataSpecific)){
                        Requirement::insert($dataSpecific);
                    }
                }

                Alert::toast('Sukses mengubah data', 'success');
            } else {
                Alert::toast('Terjadi kesalahan!', 'error');
            }
        } else {
            Alert::toast('Basic requirement harus diisi minimal satu', 'warning');
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
            $career = Career::findOrFail($request->id);
            Requirement::where('uuid', '=', $career->requirement_uuid)->delete();
            $delete = $career->delete();
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
        $uuids = $request->uuid;
        if(isset($ids)){
            $career = Career::whereIn('id', $ids);
            $delete = $career->delete();
            if($delete) {
                Requirement::whereIn('uuid', $uuids)->delete();
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
        return Category::where('category_for', '=', 'career')->select('id', 'category')->get();
    }

    public function addCategory(Request $request)
    {
        $category = new Category();
        $category->category_for = 'career';
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


    public function applicant()
    {
        return view('admin.career.applicant');
    }

    public function applicantDatatable()
    {
        $applicant = Applicant::orderBy('created_at', 'asc')->get();
        return DataTables::of($applicant)
            ->addColumn('checkboxes', 'admin.partial.checkbox')
            ->editColumn('name', function($a) {
                return '
                    <h6>'.$a->name.'</h6>
                    <span>'.$a->email.'</span><br/>
                    <span>'.$a->phone.'</span>
                ';
            })
            ->editColumn('document_link', function($a) {
                return '<a href="'.$a->document_link.'" target="_blank">'.$a->document_link.'</a>';
            })
            ->addColumn('job', function($a) {
                return '<a href="'.route('admin.career.show', $a->job->id).'" target="_blank">'.$a->job->job_title.'</a>';
            })
            ->editColumn('created_at', function($career) {
                return $career->created_at->diffForHumans();
            })
            ->rawColumns([
                'name' => 'name',
                'document_link' => 'document_link',
                'job' => 'job',
                'checkboxes' => 'checkboxes',
            ])->make();
    }

    public function applicantDestroy(Request $request)
    {
        $ids = $request->id;
        if(isset($ids)){
            $applicant = Applicant::whereIn('id', $ids);
            $delete = $applicant->delete();
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

}
