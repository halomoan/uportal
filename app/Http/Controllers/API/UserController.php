<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\FAUser;
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

        $qtype = \Request::get('qtype');
        $qname = \Request::get('qname');
        $qcompany = \Request::get('qcompany');
        $qemail = \Request::get('qemail');
        $qgroup = \Request::get('qgroup');

        //$group = explode(",", $qgroup);


        $and = "";
        $where = "";
        $filter = "";

        if (isset($qtype)) {
            $filter .=  " $and a.type = '$qtype'";
            $and = "AND";
        } else {
            $filter .=  " $and a.type = 'person'";
            $and = "AND";
        }

        if (isset($qname)) {
            $filter .=  " $and a.name like '%$qname%'";
            $and = "AND";
        }
        if (isset($qcompany)) {
            $filter .= " $and a.company like '%$qcompany%'";
            $and = "AND";
        }
        if (isset($qemail)) {
            $filter .= " $and a.email like '%$qemail%'";
            $and = "AND";
        }

        if (isset($qgroup)) {
            $filter .= " $and b.id in ($qgroup)";
            $and = "AND";
        }
        if ($and) {
            $where = $filter;
        }



        if ($search = \Request::get('q')) {


            return User::where(function ($query) use ($search, $qtype) {
                //$query->where('name', 'LIKE', "%$search%")->orWhere('email', 'LIKE', "%$search%");
                $query->whereLike(['name', 'email'], $search)->where('type', '=', $qtype);
            })->paginate(10);
        } else {

            if (\Request::get('page')) {
                if ($where) {
                    // \DB::listen(function ($sql) {
                    //     var_dump($sql);
                    // });

                    return DB::table('users as a')
                        //->select('a.*', 'b.name as group')
                        ->selectRaw('DISTINCT a.*')
                        ->leftJoin('group_user as c', 'a.id', '=', 'c.user_id')
                        ->leftJoin('groups as b', 'c.group_id', '=', 'b.id')
                        ->whereRaw($where)
                        ->orderBy('a.created_at', 'desc')
                        ->paginate(10);
                    // return DB::table('users as a')->whereRaw($where)->paginate(10);
                } else {
                    return DB::table('users as a')
                        //->select('a.*', 'b.name as group')
                        ->selectRaw('DISTINCT a.*')
                        ->leftJoin('group_user as c', 'a.id', '=', 'c.user_id')
                        ->leftJoin('groups as b', 'c.group_id', '=', 'b.id')
                        ->orderBy('a.created_at', 'desc')
                        ->paginate(10);
                    //return User::latest()->paginate(10);
                    //return DB::table('users as a')->paginate(10);
                }
            } else {
                // if ($where) {
                //     return DB::table('users as a')
                //         ->select('a.*')
                //         ->leftJoin('group_user as c', 'a.id', '=', 'c.user_id')
                //         ->leftJoin('groups as b', 'c.group_id', '=', 'b.id')
                //         ->whereRaw($where)
                //         ->orderBy('a.name', 'asc')
                //         ->get();
                // } else {
                //     return DB::table('users as a')
                //         ->select('a.*', 'b.name as group')
                //         ->leftJoin('group_user as c', 'a.id', '=', 'c.user_id')
                //         ->leftJoin('groups as b', 'c.group_id', '=', 'b.id')
                //         ->orderBy('a.name', 'asc')
                //         ->get();

                // }                
                return DB::table('users as a')->whereRaw($where)->orderBy('name')->get();
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
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:8|max:191',
            'repassword' => 'required|string|min:8|max:191',
            'groups' => 'required|array',
            'fauser' => 'required|boolean',
            'fagroup' => 'required|integer',
        ]);



        $user =  User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'company' => $request['company'],
            'type' => $request['type'],
            'urole' => $request['urole'],
            'password' => Hash::make($request['password'])
        ]);

        //if ($request['type'] == 'phone') {
        if ($user->type == 'phone') {

            DB::update('update users set email_verified_at  = now() where id = ?', [$user->id]);
        }

        // if ($request['groups']) {
        //     $groups = Group::find($request['groups']);
        //     $user->groups()->sync($groups);
        // }

        if ($request['groups']) {

            $req_groups = $request['groups'];
            array_push($req_groups, $request['fagroup']);


            $groups = Group::find($req_groups);
            $user->groups()->sync($groups);
        } else {
            $req_groups = [];
            array_push($req_groups, $request['fagroup']);

            $groups = Group::find($req_groups);
            $user->groups()->sync($groups);
        }

        $fauser = FAUser::updateOrCreate(['user_id' => $user->id], ['active' => $request['fauser'], 'companies' => implode(',', $request['facocodes'])]);

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

        $groups = $user->groups()->get()->toArray();
        $user['groups'] = array_filter(
            $groups,
            function ($group) {
                return $group['type'] == 'group';
            }
        );

        $fagroup = array_filter(
            $groups,
            function ($group) {
                return $group['type'] == 'barcd';
            }
        );

        $user['fagroup']  = array_pop($fagroup)['id'];

        $fauser = $user->fauser()->first();
        if ($fauser) {
            $facocodes = $fauser->companies;
            $user['facocodes'] = explode(',', $facocodes);
            $user['fauser'] = $fauser->active;
        } else {
            $user['facocodes'] = [];
            $user['fauser'] = false;
        }

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
            'fauser' => 'required|boolean',
            'fagroup' => 'required|integer',

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

            $req_groups = $request['groups'];
            array_push($req_groups, $request['fagroup']);


            $groups = Group::find($req_groups);
            $user->groups()->sync($groups);
        } else {
            $req_groups = [];
            array_push($req_groups, $request['fagroup']);

            $groups = Group::find($req_groups);
            $user->groups()->sync($groups);
        }



        $fauser = FAUser::updateOrCreate(['user_id' => $user->id], ['active' => $request['fauser'], 'companies' => implode(',', $request['facocodes'])]);


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
