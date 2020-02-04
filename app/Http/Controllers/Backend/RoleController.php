<?php

namespace App\Http\Controllers\Backend;

use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Role::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = Role::firstOrCreate(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        if($role) {
            Alert::toast('Sukses menambah data', 'success');
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
    public function edit(Request $request)
    {
        return Role::where('id', '=', $request->id)->with('permissions')->firstOrFail();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $role = Role::findOrFail($request->id);
        $role->name = $request->name;
        $role->syncPermissions($request->permissions);

        $save = $role->save();

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $role = Role::findOrFail($request->id);
        $delete = $role->delete();
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
