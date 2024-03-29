<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;

class GroupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        $this->authorize('isAdmin');

        if ($search = \Request::get('q')) {

            return Group::where(function ($query) use ($search) {
                //$query->where('name', 'LIKE', "%$search%")->orWhere('email', 'LIKE', "%$search%");
                $query->whereLike(['name'], $search);
            })->paginate(10);
        } else if ($type = \Request::get('t')) {
            return Group::where('type', '=', $type)->get();
        } else {

            if (\Request::get('page')) {
                return Group::where('type', '=', 'group')->latest()->paginate(10);
            } else {
                return Group::where('type', '=', 'group')->orderBy('name')->get();
                //return Group::select('*', DB::Raw("'group' AS member"))->orderBy('name')->get();
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('isAdmin');
        $this->validate($request, [
            'name' => 'required|string|max:50|unique:groups',
        ]);


        return Group::create([
            'name' => $request['name'],
            'is_default' => $request['is_default'] ? $request['is_default'] : false,
            'is_enabled' => $request['is_enabled'] ? $request['is_enabled'] : false,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('isAdmin');
        $group = Group::findOrFail($id);

        return $group;
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
        $this->authorize('isAdmin');
        $group = Group::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:50',
        ]);

        $group->update($request->all());
        return ['message' => 'Success'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');

        $user = Group::findOrFail($id);

        $user->delete();

        return ['message' => 'User Deleted'];
    }
}
