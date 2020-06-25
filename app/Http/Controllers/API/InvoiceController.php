<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Invoice;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
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
        $search = \Request::get('q');
        $year = \Request::get('y');
        $years = \Request::get('years');
        $new = \Request::get('n');

        if ($years) {
            return auth('api')->user()->invoices()->select('year')->groupBy('year')->limit(3)->pluck('year');
        }

        if ($search) {

            return auth('api')->user()->invoices()->where(function ($query) use ($search) {
                $query->whereLike(['invno', 'desc', 'filename'], $search);
            })->where('published','=',1)->paginate(10);
        } elseif ($new) {
            return (auth('api')->user()->invoices()
                ->where('unread', true)
                ->where('published','=',1)
                ->count() > 0);
        } else {
            return auth('api')->user()->invoices()
                ->where('year', $year)
                ->where('published','=',1)
                ->orderBy('invdate', 'desc')
                ->orderBy('unread', 'desc')
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

        $user = auth('api')->user();



        $prefix = hash('md5', $user->email);

        foreach (glob(storage_path('app\\public\\inv\\' . $prefix . '*')) as $filename) {
            unlink($filename);
        }

        $newFile = $prefix . hash('sha1', $user->password) . strtotime("tomorrow") . ".pdf";


        // \DB::listen(function($sql) {
        //     var_dump($sql);
        // });

        //Query Database based on $id
        $invfile = "";
        if ($user->urole === 'admin' ){
            $invfile = Invoice::select('filename')->findOrFail($id)->first();
        } else {
            $invfile = $user->invoices()->select('filename')->findOrFail($id)->first();
        }        
        

        $invdir = "";  
        if ($user->urole === 'admin' ){
            $invdir = Invoice::findOrFail($id)->invoiceh()->select('CoCode', 'month', 'year')->first();
        } else {
            
            $invdir =  $user->invoices()->findOrFail($id)->invoiceh()->select('CoCode', 'month', 'year')->first();
        }        
                  

        $fileName = $invfile['filename'];
        $dir = $invdir['CoCode'] . '\\' . $invdir['year'] . $invdir['month'] . '\\';


        $sfilePath = storage_path('invoices\\' . $dir . $fileName);
        if (file_exists($sfilePath)) {
            //Remember to create INV folder in the Public folder
            $dfilePath = storage_path('app\\public\\inv\\' . $newFile);

            copy($sfilePath,  $dfilePath);
            return Storage::url('inv//' . $newFile);
        }
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
