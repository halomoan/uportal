<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TimelineController extends Controller
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
        $limit = 3;
        $page = \Request::get('page');

        $result = auth()->user()->timelines()->select(DB::raw('timelines.*'))
            ->orderBy('created_at', 'desc')
            ->offset($limit * $page)->limit($limit)->get();



        $groups = auth()->user()->groups()->get();

        foreach ($groups as $group) {
            $gresult =
                $group->timelines()->select(DB::raw('timelines.*'))
                ->offset($limit * $page)->limit($limit)->get();

            $result = $result->merge($gresult);
        }

        $timelines = [];
        foreach ($result->all() as $item) {

            $date = Carbon::parse($item->created_at);

            $body = $item->body()->first();

            $id = $item->id;
            if ($item->news_id) {
                $id = $item->news_id;
            }
            $id = $item->news_id;

            $timeline = [
                'id' => $id,
                'date' => $date->format('D, d M. Y'),
                'created' => $date->diffForHumans(),
                'from' => $item->from,
                'title' => $item->title,
                'body' => ($body ? $body->body : ""),
                'type' => $item->type,
                'link' => $item->link ? $item->link : "",
                'linktext' =>
                $item->linktext ? $item->linktext : "Show Me",
                'param1' => $item->param1

            ];

            $timelines[] = $timeline;
        }
        return $timelines;
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
