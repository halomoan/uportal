<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\News;
use App\Group;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $since = \Request::get('t');

        $where = "";

        switch ($since) {
            case 'today':
                $where = 'DATE(created_at) = CURDATE()';
                break;
            case 'thismth':
                $period1 = date('m');
                $where = 'Month(created_at) = ' . $period1;
                break;
            case 'upto2mths':
                $period1 = date('m');
                $period2 = date("m", strtotime('-1 months'));
                $where = 'Month(created_at) Between ' . $period2  . ' and ' . $period1;
                break;
            case 'upto1year':
                $period1 = date('Y');
                $where = 'Year(created_at) = ' . $period1;
                break;
            case 'upto2years':
                $period1 = date('Y');
                $period2 = date('Y') - 1;
                $where = 'Year(created_at) Between ' . $period2  . ' and ' . $period1;
                break;
            default:
                $where = 'DATE(created_at) = CURDATE()';
                break;
        }

        if ($search) {

            return auth()->user()->mynews()->where(function ($query) use ($search) {
                $query->whereLike(['title'], $search);
            })->whereRaw($where)->orderBy('validFrom', 'asc')->paginate(10);
        } else {
            return auth()->user()->mynews()
                ->whereRaw($where)
                ->orderBy('validFrom', 'asc')
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
        $news['publishGroup'] = $news->groups()->select('id','name','type')->get();
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

        $this->authorize('isAdmin');
        //$user = auth('api')->user();

        $news = News::findOrFail($id);

        $toUser = \Request::get('toUser');
        $toGroup = \Request::get('toGroup');

        if ($toUser || $toGroup) {
            if ($toGroup) {
                $groups = Group::find($toGroup);
                $news->groups()->sync($groups);                
            }
        } else {
            $this->validate($request, [
                'title' => 'required|string|max:191',
                'description' => 'required|string',
                'author' => 'sometimes|string',
                'showAuthor' => 'required|boolean',
                'validFrom' => 'required|date',
                'validTo' => 'required|date',
            ]);

            //return $request['validFrom'];
            $validFrom = Carbon::parse($request['validFrom'], 'UTC');

            //return $validFrom->isoFormat("YYYY-MM-DD HH:mm:ss");


            $validTo = Carbon::parse($request['validTo'], 'UTC');
            $news->update([
                'title' => $request['title'],
                'description' => $request['description'],
                'author' => $request['author'],
                'showauthor' => $request['showAuthor'],
                'validFrom' => $validFrom->isoFormat("YYYY-MM-DD HH:mm:ss"),
                'validTo' => $validTo->isoFormat("YYYY-MM-DD HH:mm:ss")

            ]);
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

        $news = News::findOrFail($id);

        $news->delete();

        return ['message' => 'News Deleted'];
    }
}
