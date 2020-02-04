<?php

namespace App\Http\Controllers\Backend;

use Session;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Profile::all();
    }

    public function getActive()
    {
        return Profile::where('is_active', 1)->first();
    }

    public function getPreview()
    {
        return Profile::where('is_preview', 1)->first();
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
        $profile = new Profile();
        $profile->name = $request->profile;
        $add = $profile->save();

        if($add) {
            $output = [
                'status' => 'success',
                'message' => 'Berhasil menambah profil.'
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $update = Profile::where('id', '=', $request->id)->update(['name' => $request->name]);

        if($update) {
            $output = [
                'status' => 'success',
                'message' => 'Berhasil mengubah profil.'
            ];
        } else {
            $output = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan!'
            ];
        }

        return $output;
    }


    public function updateActive(Request $request)
    {
        $update = Profile::where('id', '=', $request->id)->update(['is_active' => 1]);

        if($update) {
            $this->setActive($request->id);
            $output = [
                'status' => 'success',
                'message' => 'Berhasil memilih profil.'
            ];
        } else {
            $output = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan!'
            ];
        }

        return $output;
    }


    public function updatePreview(Request $request)
    {
        $update = Profile::where('id', '=', $request->id)->update(['is_preview' => 1]);

        if($update) {
            $this->setPreview($request->id);
            $output = [
                'status' => 'success',
                'message' => 'Berhasil memilih profil.'
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
        $is_active = Profile::where([
            ['id', '=', $request->id],
            ['is_active', '=', 1]
        ])->orWhere([
            ['id', '=', $request->id],
            ['is_preview', '=', 1]
        ])->exists();

        $delete = Profile::findOrFail($request->id)->delete();

        if($delete) {
            if($is_active){
                $this->resetDefault();
            }

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


    public function resetDefault() {
        $this->setActive(1);
        $this->setPreview(1);
    }

    
    public function setActive($id)
    {
        Profile::where('id', '=', $id)->update(['is_active' => 1]);
        Profile::where('id', '!=', $id)->update(['is_active' => 0]);
        Session::forget('profile-preview');
    }

    public function setPreview($id)
    {
        Profile::where('id', '=', $id)->update(['is_preview' => 1]);
        Profile::where('id', '!=', $id)->update(['is_preview' => 0]);
        Session::forget('profile-preview');
    }

}
