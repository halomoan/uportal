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

        $flag = $this->getAnnounceFlag();
        array_push($flags, $flag);

        $flag = $this->getInvoiceFlag();
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

    protected function getInvoiceFlag()
    {
        $invoices = auth()->user()->invoices()
            ->where('unread', true)->get();

        $flag = ['name' => 'INVOICES', 'value' => ($invoices->count() > 0)];

        return $flag;
    }

    protected function getAnnounceFlag()
    {
        $userId = auth()->user()->id;

        $groups = DB::table('group_user')->where('user_id', $userId)->select('group_id as id')->get()->toArray();
        $groupsId = array_column($groups, 'id');

        $userNews = DB::table('news_user')->select('news.id', 'read_news.news_id')->where('news_user.user_id', $userId)
            ->join('news', function ($join) {
                $join->on('news_user.news_id', '=', 'news.id')
                    ->orderBy('news.validFrom', 'desc')
                    ->limit(5);
            })
            ->leftJoin('read_news', function ($join) use ($userId) {
                $join->on('read_news.news_id', '=', 'news.id')
                    ->where('read_news.user_id', '=', $userId);
            })->whereNull('read_news.news_id');

        $allNews = DB::table('group_news')->select('news.id', 'read_news.news_id')->whereIn('group_news.group_id', $groupsId)
            ->join('news', function ($join) {
                $join->on('group_news.news_id', '=', 'news.id')
                    ->orderBy('news.validFrom', 'desc')
                    ->limit(5);
            })
            ->leftJoin('read_news', function ($join) use ($userId) {
                $join->on('read_news.news_id', '=', 'news.id')
                    ->where('read_news.user_id', '=', $userId);
            })->whereNull('read_news.news_id')
            ->union($userNews)->get();


        $flag = ['name' => 'ANNOUNCE', 'value' => ($allNews->count() > 0)];
        return $flag;
    }
}
