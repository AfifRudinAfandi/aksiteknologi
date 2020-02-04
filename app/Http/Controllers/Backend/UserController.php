<?php

namespace App\Http\Controllers\Backend;

use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Alert;
use JsValidator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rule = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'role' => 'required',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required'
        ];

        $message = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute sudah terdaftar',
            'email.email' => 'Format email salah',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok',
            'password.min' => ':attribute harus minimal :min karakter'
        ];

        $attribute = [
            'name' => 'Nama',
            'email' => 'E-Mail',
            'username' => 'Nama pengguna',
            'role' => 'Role',
            'password' => 'Kata sandi',
            'password_confirmation' => 'Konfirmasi kata sandi',
        ];

        $validator = JsValidator::make($rule, $message, $attribute);
        return view('admin.user.index')
            ->withRoles(Role::orderBy('created_at', 'asc')->get())
            ->withPermissions(Permission::orderBy('created_at', 'asc')->get())
            ->withValidator($validator);
    }

    public function datatable()
    {
        $users = User::all();
        return DataTables::of($users)
                ->addColumn('checkboxes', 'admin.partial.checkbox')
                ->editColumn('created_at', function($user) {
                    return [
                        'display' => $user->created_at->diffForHumans(),
                        'timestamp' => $user->created_at,
                    ];
                })
                ->addColumn('role', function($user) {
                    return $user->getRoleNames();
                })
                ->editColumn('updated_at', function($user) {
                    return [
                        'display' => $user->updated_at->diffForHumans(),
                        'timestamp' => $user->updated_at,
                    ];
                })    
                ->addColumn('actions', 'admin.user.actions')
                ->rawColumns(['checkboxes' => 'checkboxes', 'actions' => 'actions'])
                ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->email_verified_at = Carbon::now();
        $user->password = Hash::make($request->password);
        $user->assignRole($request->role);

        $save = $user->save();
        if($save) {
            Alert::toast('Sukses menambahkan data', 'success');
        } else {
            Alert::toast('Terjadi kesalahan!', 'error');
        }
        return redirect()->route('admin.user.index');
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'role' => 'required',
            'password' => 'confirmed|min:6',
        ];

        $message = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute sudah terdaftar',
            'email.email' => 'Format email salah',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok',
            'password.min' => ':attribute harus minimal :min karakter'
        ];

        $attribute = [
            'name' => 'Nama',
            'email' => 'E-Mail',
            'username' => 'Nama pengguna',
            'role' => 'Role',
            'password' => 'Kata sandi',
            'password_confirmation' => 'Konfirmasi kata sandi',
        ];

        $user = User::findOrFail($id);

        $validator = JsValidator::make($rule, $message, $attribute);
        return view('admin.user.edit')
            ->withRoles(Role::orderBy('created_at', 'asc')->get())
            ->withUser($user)
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
        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->roles()->detach();
        $user->assignRole($request->role);

        if(isset($request->password)){
            $user->password = Hash::make($request->password);
        }

        $save = $user->save();
        if($save) {
            Alert::toast('Sukses mengubah data', 'success');
        } else {
            Alert::toast('Terjadi kesalahan!', 'error');
        }
        return redirect()->route('admin.user.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(isset($request->id)){
            $user = User::findOrFail($request->id);
            $delete = $user->delete();
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
            $user = User::whereIn('id', $ids);
            $delete = $user->delete();
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
