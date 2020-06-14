<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\TLBody;
use Illuminate\Http\Request;
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
        $items = auth()->user()->timelines()
            ->orderBy('created_at', 'desc')
            ->offset($limit * $page)->limit(3)->get();

        $timelines = [];

        foreach ($items as $item) {

            $date = Carbon::parse($item->created_at);

            $body = $item->body()->first();
            $timeline = [
                'id' => $item->id,
                'date' => $date->format('D, d M. Y'),
                'created' => $date->diffForHumans(),
                'from' => $item->from,
                'title' => $item->title,
                'body' => ($body ? $body->body : ""),
                'type' => $item->type,
                'link' => $item->link ? $item->link : "",
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
