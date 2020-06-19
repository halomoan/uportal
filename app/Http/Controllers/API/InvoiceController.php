<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Invoice;
use Illuminate\Support\Facades\Storage;

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
        $new = \Request::get('n');

        if ($search) {

            return auth()->user()->invoices()->where(function ($query) use ($search) {
                $query->whereLike(['inv_no', 'title', 'filename'], $search);
            })->paginate(10);
        } elseif ($new) {
            return (auth()->user()->invoices()
                ->where('unread', true)
                ->count() > 0);
        } else {
            return auth()->user()->invoices()
                ->where('year', $year)
                ->orderBy('inv_date', 'desc')
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
        
        
         $prefix = hash('md5', auth()->user()->email);
         
         foreach (glob(storage_path('app\\public\\inv\\' . $prefix . '*' )) as $filename) {
            unlink($filename);
        }         

         $newFile = $prefix . hash('sha256', auth()->user()->password) . strtotime("tomorrow") . ".pdf";
         

         //Query Database based on $id
         $fileName = 'STARHUB.pdf';

         $sfilePath = storage_path('invoices\\' . $fileName);
         $dfilePath = storage_path('app\\public\\inv\\' . $newFile );

       
        
         copy($sfilePath,  $dfilePath);
         return Storage::url('inv//' . $newFile);
        
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
