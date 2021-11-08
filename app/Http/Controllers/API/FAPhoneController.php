<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class FAphoneController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['show']]);
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
            'phoneid' => 'required',
            'deregister' => 'required'
        ]);

        $id = $request->get('phoneid');
        $domain = config('app.domain');
        $email = $id . '@' . $domain;

        $user = User::where('email', '=', $email)->first();
        if ($user != null) {
            $user->delete();
            return response(['status' => true]);
        }

        return response(['status' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $domain = config('app.domain');
        $email = $id . '@' . $domain;

        $user = User::where('email', '=', $email)->first();

        if ($user != null) {
            if ($user->type == 'phone') {
                return response(['status' => true]);
            } else {
                return response(['status' => false]);
            }
        }
        return response(['status' => false]);
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
