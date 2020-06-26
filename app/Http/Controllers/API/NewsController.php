<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\News;
use App\User;
use App\Group;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\MyLibs\Timeliner;
use Facade\FlareClient\Time\Time;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class NewsController extends Controller
{

    private $AUTHORS = ['admin', 'author'];
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

        $user = auth('api')->user();
        $role = $user->urole;

        $news = [];

        $userId = $user->id;
        $groups = $user->groups()->select('id')->get();
        $isAuthor =
            \Request::get('auth');
        $search = \Request::get('q');
        $since = \Request::get('t');
        $perpage = \Request::get('up');
        $page = \Request::get('page') - 1;

        if (!$perpage) {
            $perpage = 5;
        }

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

            if ($isAuthor && in_array($role, $this->AUTHORS)) {
                return $user->mynews()->where(function ($query) use ($search) {
                    $query->whereLike(['title'], $search);
                })->whereRaw($where)->orderBy('validFrom', 'desc')->paginate($perpage);
            } else {
            }
        } else {
            if ($isAuthor && in_array($role, $this->AUTHORS)) {
                $news =  $user->mynews()
                    ->whereRaw($where)
                    ->orderBy('validFrom', 'desc')->offset($page * $perpage)->limit($perpage)->get();

                foreach ($news as $item) {

                    $assigned = 0;
                    $assigned = $item->users()->count() + $item->groups()->count();
                    $item['assigned'] = ($assigned > 0);
                }
                $total = $user->mynews()
                    ->whereRaw($where)->count();
                return  new Paginator($news, $total, $perpage);
            } else {


                $news1 = News::whereHas('groups', function ($q) use ($groups) {
                    $q->whereIn('id', $groups);
                });

                $news = News::whereHas('users', function ($q) use ($userId) {
                    $q->where('id', $userId);
                })->union($news1)->orderBy('validFrom', 'desc')->limit(5)->get();


                foreach ($news as $item) {
                    DB::table('read_news')->insertOrIgnore([
                        ['user_id' => $userId, 'news_id' => $item->id]
                    ]);
                }

                $total = count($news);
                return  new Paginator($news, $total, 5);
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
        $this->authorize('isAuthor');
        $user = auth('api')->user();

        //$news = News::find(1);
        //$timeliner = new Timeliner(null, $news->groups()->get());
        //$timeliner = new Timeliner([$user]);
        //$timeliner->forNews($news);
        //return;

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
        $user->mynews()->create([
            'title' => $request['title'],
            'description' => $request['description'],
            'author' => $request['author'],
            'showauthor' => $request['showAuthor'],
            'color' => $request['color'],
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
        $this->authorize('isAuthor');
        $news = News::findOrFail($id);
        $news['publishUser'] = $news->users()->select('id', 'name', 'type')->get();
        $news['publishGroup'] = $news->groups()->select('id', 'name', 'type')->get();
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

        $this->authorize('isAuthor');
        //$user = auth('api')->user();

        $news = News::findOrFail($id);

        $toUser = \Request::get('toUser');
        $toGroup = \Request::get('toGroup');
        $setUserGroup = \Request::get('setUserGroup');

        if ($setUserGroup) {
            if ($toUser || $toGroup) {

                if ($toUser) {

                    $users = User::find($toUser);
                    $news->users()->sync($users);

                    Timeliner::getInstance()->News($news)->forUsers($users);
                } else {
                    $news->users()->detach();
                    Timeliner::getInstance()->News($news)->forUsers(null);
                }

                if ($toGroup) {
                    $groups = Group::find($toGroup);
                    $news->groups()->sync($groups);

                    Timeliner::getInstance()->News($news)->forGroups($groups);
                } else {
                    $news->groups()->detach();
                    Timeliner::getInstance()->News($news)->forGroups(null);
                }
            } else {
                $news->groups()->detach();
                $news->users()->detach();
                Timeliner::getInstance()->News($news)->forUsers(null);
                Timeliner::getInstance()->News($news)->forGroups(null);
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

            $validFrom = Carbon::parse($request['validFrom'], 'UTC');

            $validTo = Carbon::parse($request['validTo'], 'UTC');
            $news->update([
                'title' => $request['title'],
                'description' => $request['description'],
                'author' => $request['author'],
                'showauthor' => $request['showAuthor'],
                'color' => $request['color'],
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
        $this->authorize('isAuthor');

        $news = News::findOrFail($id);

        $news->groups()->detach();
        $news->users()->detach();

        DB::delete('DELETE FROM timelines WHERE news_id = ' . $news->id);

        $news->delete();

        return ['message' => 'News Deleted'];
    }
}
