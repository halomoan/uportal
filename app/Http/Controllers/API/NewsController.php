<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\News;
use Carbon\Carbon;

class NewsController extends Controller
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
        $search = \Request::get('q');

        if ($search) {
            
            return auth()->user()->news()->where(function ($query) use ($search) {
                $query->whereLike(['title'], $search);
            })->paginate(10);
        } else {            
            return auth()->user()->news()                
                ->orderBy('validFrom', 'desc')                
                ->paginate(10);
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
        $user = auth('api')->user();

        $this->validate($request, [
            'title' => 'required|string|max:191',
            'description' => 'required|string',
            'author' => 'sometimes|string',
            'showAuthor' => 'required|boolean',
            'validFrom' => 'required|date',
            'validTo' => 'required|date',
        ]);

        $validFrom = Carbon::parse($request['validFrom'], 'UTC');
        $validTo = Carbon::parse($request['validTo'], 'UTC');
        $user->news()->create([
            'title' => $request['title'],
            'description' => $request['description'],
            'author' => $request['author'],
            'showauthor' => $request['showAuthor'],
            'validFrom' => $validFrom->isoFormat("YYYY-MM-DD HH:mm:ss"),
            'validTo' => $validTo->isoFormat("YYYY-MM-DD HH:mm:ss")

        ]);
        return ['message' => 'Success'];
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
        $news = News::findOrFail($id);        
        return $news;
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
