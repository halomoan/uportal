<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
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
        $stat = \Request::get('s');
        if ($stat) {
            $stats['noOfNewInvoice'] = $this->noOfNewInvoices();
            $stats['noOfNewAnnounce'] = $this->noOfNewAnnounce();
            $stats['lastVisit'] = $this->lastVisit();
            return $stats;
        }

        $chart = \Request::get('chart');


        if ($chart && $chart == 'invoicemth') {
            $data = $this->invoiceMTHChart();
            return $data;
        }

        if ($chart && $chart == 'invoiceyoy') {
            $data = $this->invoiceYoYChart();
            return $data;
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

    protected function lastVisit()
    {

        return Carbon::parse(auth()->user()->previous_visit)->diffForHumans();
    }

    protected function noOfNewInvoices()
    {
        return auth()->user()->invoices()
            ->where('unread', true)
            ->count();
    }
    protected function noOfNewAnnounce()
    {

        $userId = auth()->user()->id;

        $groups = DB::table('group_user')->where('user_id', $userId)->select('group_id as id')->get()->toArray();
        $groupsId = array_column($groups, 'id');

        $userNews = DB::table('news_user')->select('news.id')->where('news_user.user_id', $userId)
            ->join('news', function ($join) {
                $join->on('news_user.news_id', '=', 'news.id');
            })
            ->leftJoin('read_news', function ($join) use ($userId) {
                $join->on('read_news.news_id', '=', 'news.id')
                    ->where('read_news.user_id', '=', $userId);
            })->whereNull('read_news.news_id');

        return DB::table('group_news')->select('news.id')->whereIn('group_news.group_id', $groupsId)
            ->join('news', function ($join) {
                $join->on('group_news.news_id', '=', 'news.id');
            })
            ->leftJoin('read_news', function ($join) use ($userId) {
                $join->on('read_news.news_id', '=', 'news.id')
                    ->where('read_news.user_id', '=', $userId);
            })->whereNull('read_news.news_id')
            ->union($userNews)->count();
    }

    protected function invoiceMTHChart()
    {

        $Date1 = Carbon::now()->subMonths(2);
        $Date2 = Carbon::now()->subMonths(1);
        $Date3 = Carbon::now();
        $labels = [
            $Date1->format('M-Y'),
            $Date2->format('M-Y'),
            $Date3->format('M-Y'),
        ];

        $months = [
            $Date1->format('m'),
            $Date2->format("m"),
            $Date3->format("m")
        ];

        $invoices = auth()->user()->invoices()
            ->select(DB::RAW('MONTH(inv_date) as month'), 'amount')
            ->where('year', $Date1->format('Y'))
            ->whereIn(DB::raw('MONTH(inv_date)'), $months)
            ->orderBy('inv_date', 'desc')
            ->get();

        $data = [0, 0, 0];
        foreach ($invoices as $invoice) {
            if ($invoice->month == $months[0] && $data[0] == 0) {
                $data[0] = $invoice->amount;
            }
            if ($invoice->month == $months[1] && $data[1] == 0) {
                $data[1] = $invoice->amount;
            }
            if ($invoice->month == $months[2] && $data[2] == 0) {
                $data[2] = $invoice->amount;
            }
        }

        $perctg = $data[1] > 0 ? (($data[2] - $data[1]) / $data[1]) * 100 : 0;

        $amount = $data[2];

        $chartData['perctg'] = $perctg;
        $chartData['amount'] = $amount;
        $chartData['labels'] = $labels;
        $chartData['data'] = $data;

        return $chartData;
    }

    protected function invoiceYoYChart()
    {


        $Date1 = Carbon::now()->subMonths(12);
        $Date2 = Carbon::now();
        $labels = [
            $Date1->format('M-Y'),
            $Date2->format('M-Y'),
        ];

        $years = [
            $Date1->format("Y"),
            $Date2->format("Y")
        ];

        $invoices = auth()->user()->invoices()
            ->select(DB::RAW('YEAR(inv_date) as year'), DB::RAW('MONTH(inv_date) as month'), 'amount')
            ->whereIn(DB::raw('YEAR(inv_date)'), $years)
            ->where(DB::raw('MONTH(inv_date)'), $Date1->format('m'))
            ->orderBy('inv_date', 'desc')
            ->get();

        $data = [0, 0];
        foreach ($invoices as $invoice) {
            if ($invoice->year == $years[0] && $data[0] == 0) {
                $data[0] = $invoice->amount;
            }
            if ($invoice->year == $years[1] && $data[1] == 0) {
                $data[1] = $invoice->amount;
            }
        }

        $perctg = $data[0] > 0 ? (($data[1] - $data[0]) / $data[0]) * 100 : 0;

        $amount = $data[1];

        $chartData['perctg'] = $perctg;
        $chartData['amount'] = $amount;
        $chartData['labels'] = $labels;
        $chartData['data'] = $data;

        return $chartData;
    }
}
