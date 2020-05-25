<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Group;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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

            return User::where(function ($query) use ($search) {
                //$query->where('name', 'LIKE', "%$search%")->orWhere('email', 'LIKE', "%$search%");
                $query->whereLike(['name', 'email'], $search);
            })->paginate(10);
        } else {

            return User::latest()->paginate(10);
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
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:8|max:191',
            'repassword' => 'required|string|min:8|max:191',
            'groups' => 'required|array',
        ]);

        $user =  User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'type' => $request['type'],
            'password' => Hash::make($request['password'])
        ]);

        if ($request['groups']) {
            $groups = Group::find($request['groups']);
            $user->groups()->detach($groups);
            $user->groups()->attach($groups);
        }

        return $user;
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
        $user = User::findOrFail($id);
        $user['groups'] = $user->groups()->get();
        return $user;
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
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8|max:191',
            'repassword' => 'sometimes|string|min:8|max:191',
            'groups' => 'required|array',

        ]);

        if ($request->repassword) {
            if ($request->repassword == $request->password) {
                $request->merge(['password' => Hash::make($request->password)]);
            } else {

                $errors['repassword'] = ['Not match with the password'];
                $message = ['message' => 'Password does not match', 'errors' => $errors];
                return response()->json($message, 422);
            }
        }

        $user->update($request->all());

        if ($request['groups']) {
            $groups = Group::find($request['groups']);
            $user->groups()->detach($groups);
            $user->groups()->attach($groups);
        }

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

        $user = User::findOrFail($id);

        $user->delete();

        return ['message' => 'User Deleted'];
    }
}
