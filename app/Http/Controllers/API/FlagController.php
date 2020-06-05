<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\News;
use Illuminate\Support\Facades\DB;

class FlagController extends Controller
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
        $flags = [];

        $invoices = auth()->user()->invoices()
            ->where('unread', true)->get();


            $userId = auth()->user()->id;
            $groups = auth()->user()->groups()->select('id')->get()->toArray();

            $groupsId = join(",",$groups);

            $sql = 'SELECT news_id FROM users INNER JOIN news_user ON users.id = news_user.user_id WHERE users.id = :userId
            UNION
            SELECT news_id FROM groups INNER JOIN group_news ON groups.id = group_news.group_id WHERE groups.id in (:groupIds)';

            $results = DB::select( DB::raw($sql), array(
                'userId' => $userId,
                'groupIds' => $groupsId
            ));

            return $results;

        $flag = ['name' => 'INVOICES', 'value' => ($invoices->count() > 0)];
        $flag = ['name' => 'ANNOUNCE', 'value' => true];
        array_push($flags, $flag);

        return ['flags' => $flags];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
