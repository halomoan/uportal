<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\InvoiceH;

class ImpInvoiceController extends Controller
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
        $years = \Request::get('years');
        $year = \Request::get('year');
        $cocode = \Request::get('cocode');

        if ($years && $cocode) {
            return InvoiceH::select('year')->where('CoCode', '=', $cocode)
                ->groupBy('year')->get();
        }

        if ($year && $cocode) {
            //return InvoiceH::select('id', 'Year', 'Month', 'TotRecord', 'Status', 'updated_at')->where('CoCode', '=', $cocode)
            return InvoiceH::where('CoCode', '=', $cocode)
                ->where('Year', '=', $year)->get();
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
        $this->validate($request, [
            'year' => 'required|string|max:4',
            'cocode' => 'required|integer',
        ]);

        $cocode = $request['cocode'];
        $year = $request['year'];

        $invoiceh = InvoiceH::where('CoCode', '=', $cocode)
            ->where('year', '=', $year)->count();

        if ($invoiceh > 0) {
            $errors['year'] = ['Year: ' . $year . ' Exists For The Selected Company'];
            $message = ['message' => 'Redundant Year', 'errors' => $errors];
            return response()->json($message, 422);
        }

        for ($month = 1; $month <= 12; $month++) {

            InvoiceH::create([
                'CoCode' => $cocode,
                'Month' => $month,
                'Year' => $year,
                'TotRecord' => 0
            ]);
        }
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

        $this->authorize('isAdmin');

        list($year, $cocode) = explode(',', $id);

        if ($year && $cocode) {
            return InvoiceH::where('CoCode', '=', $cocode)
                ->where('year', '=', $year)->delete();
        }
    }
}
