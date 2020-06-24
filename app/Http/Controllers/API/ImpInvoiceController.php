<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\InvoiceH;
use App\InvoiceL;
use App\Invoice;
use App\User;

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
                'Month' => sprintf("%02d", $month),
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
        $this->authorize('isAdmin');

        list($month, $year, $cocode) = explode(',', $id);
        if ($month && $year && $cocode) {
            $dir = storage_path('invoices\\' . $cocode . '\\' . $year . $month . '\\');

            if (is_dir($dir)) {
                $metafile = $dir . 'metadata.json';
                if (file_exists($metafile)) {
                    $metacontent = file_get_contents($metafile);
                    $metadata = json_decode($metacontent, true);                    

                
                    if( ! isset( $metadata['CoCode'] ) || ! isset( $metadata['Month'] ) || ! isset( $metadata['Year'] ) ) {
                                              
                        $this->retStatus('Invalid Metadata', 'Metadata Format Is Invalid');   
                    };

                    if ( $cocode != $metadata['CoCode'] || $month != $metadata['Month'] || $year != $metadata['Year']){                      
                        $this->retStatus('Invalid Metadata', 'Metadata Is For Wrong Entity/Period');   
                    }

                    // Get InvoiceH id
                    $invoiceh = InvoiceH::where('CoCode', '=', $cocode)
                        ->where('year','=',$year)
                        ->where('month','=',$month)
                        ->first();

                    if (! isset($invoiceh) ) {
                        if ( $cocode != $metadata['CoCode'] || $month != $metadata['Month'] || $year != $metadata['Year']){
                            
                            $this->retStatus('Invalid Header', 'Cannot Find The Header For The Entity/Period');   
                            
                        }
                    }

                    //Loop Metadata
                    if(array_key_exists('Items', $metadata)) {
                        foreach ($metadata['Items'] as $key => $value) {
                            $CustNo = $value['CustNo'];
                            $CustName = $value['CustName'];
                            $Email= $value['Email'];
                            $InvNo = $value['InvNo'];
                            $InvDate = $value['InvDate'];
                            $Desc = $value['Desc'];
                            $Amount = $value['Amount'];
                            $Filename = $value['Filename'];

                            if (file_exists($dir . $Filename)){
                                $user = User::where('email', '=', $Email)->first();

                                if (isset($user)){
                                    $invoice = [
                                        'user_id' => $user->id,
                                        'invoiceh_id' => $invoiceh->id,
                                        'invno' => $InvNo,
                                        'invdate' => $InvDate,
                                        'year' => $year,
                                        'desc' => $Desc,
                                        'amount' => $Amount,
                                        'filename' => $Filename,
                                        'unread' => 1,
                                        'published' => 0
    
                                    ];

                                    Invoice::create($invoice);
                                } else{
                                    $this->logImport($invoiceh->id,"Can't Find Email : ". $Email );
                                }
    
                            } else{
                                $this->logImport($invoiceh->id,"Can't Find File : ". $Filename );
                            }

                        
                        }
                    } else {
                        // No Items in the Metadata
                        $this->retStatus('Invalid Metadata', 'Metadata Has No Items');                    
                    }
                   
                } else {
                    // Metadata Not Found                  
                    $this->retStatus('Invalid Metadata', 'Metadata Not Found');
                }
            } else {
                // Directory Not Found                
                $this->retStatus('Invalid Path', 'Path Not Found');
            }
        }
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


    private function logImport($id, $text){
        InvoiceL::create([
            'invoiceh_id' => $id,
            'text' => $text
        ]);
    }

    private function retStatus($text,$subtext){
        $errors['msg'] = [$subtext];
        $message = ['message' => $text];
        return response()->json($message, 422);
    }
}
