<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class FACodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $this->validate($request, [
            'codes' => 'sometimes',
            'files' => 'sometimes',
            'files.*' => 'image|mimes:png,jpeg|max:2048'
        ]);


        $codes = $request->get('codes');

        foreach ($codes as $code) {
            // return response(['status' => $code]);
        }


        if ($request->hasfile('files')) {

            foreach ($request->file('files') as $image) {
                $name = $image->getClientOriginalName();

                $currentPhoto = public_path('/storage/faimages/') . $name;
                $prefix = '';
                if (file_exists($currentPhoto)) {
                    //@unlink($currentPhoto);
                    $prefix = 'N';
                }

                //\Image::make($request->photo)->save(public_path('storage/faimages/') . $name)->fit(800, 800);
                $image->move(public_path('storage/faimages/'), $prefix . $name);
            }
        }

        return response(['status' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($id == 'test') {
            $codeInfo = [
                'status' => true,
                'msg' => 'Successfully Connected'
            ];
        } else {
            $codeInfo = [
                'desc' => 'Machine A (Type II)',
                'loc' => 'Level 1',
                'qty' => '10',
                'acqdate' => '2020-08-10'
            ];
        }

        return $codeInfo;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
