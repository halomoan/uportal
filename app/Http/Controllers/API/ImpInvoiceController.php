<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $this->authorize('isAdmin');

        $log = \Request::get('log');
        $invoiceh = \Request::get('invoiceh');
        $years = \Request::get('years');
        $year = \Request::get('year');
        $cocode = \Request::get('cocode');
        $qinvdate = \Request::get('qinvdate');
        $qinvno = \Request::get('qinvno');
        $qname = \Request::get('qname');
        $qemail = \Request::get('qemail');
        $qpublished = \Request::get('qpublished');
        $qunread = \Request::get('qunread');

        $perpage = \Request::get('up');

        if (!$perpage) {
            $perpage = 10;
        }


        if ($years && $cocode) {
            return InvoiceH::select('year')->where('CoCode', '=', $cocode)
                ->groupBy('year')->get();
        }

        if ($year && $cocode) {
            return InvoiceH::where('CoCode', '=', $cocode)
                ->where('Year', '=', $year)->get();
        }

        if ($invoiceh) {

            // $sql = "SELECT b.id,b.invno,b.invdate,b.desc,b.amount,b.filename,b.published,c.name,c.email FROM invoiceh as a INNER JOIN invoices as b ON a.id = b.invoiceh_id ";
            // $sql .= "INNER JOIN users AS C ON b.user_id = c.id WHERE a.id = :id";
            // $result = DB::select($sql,['id' => $invoiceh]);
            // $total = count($result);
            // return  new Paginator($result, $total, $perpage);

            $where = "a.id = $invoiceh";

            $and = "";
            $filter = "(";

            if (isset($qinvdate)) {
                $filter .=  " $and b.invdate = '$qinvdate'";
                $and = "AND";
            }
            if (isset($qinvno)) {
                $filter .= " $and b.invno like '%$qinvno%'";
                $and = "AND";
            }
            if (isset($qname)) {
                $filter .= " $and c.name like '%$qname%'";
                $and = "AND";
            }

            if (isset($qemail)) {
                $filter .= " $and c.email like '%$qemail%'";
                $and = "AND";
            }

            if (isset($qpublished)) {
                $filter .= " $and b.published = '$qpublished'";
                $and = "AND";
            }

            if (isset($qunrea)) {
                $filter .= " $and b.unread = '$qunread'";
                $and = "AND";
            }

            if ($and) {
                $where .= " AND $filter)";
            }
            // \DB::listen(function ($sql) {
            //     var_dump($sql);
            // });

            return DB::table('invoiceh as a')
                ->join('invoices as b', 'a.id', '=', 'b.invoiceh_id')
                ->join('users as c', 'c.id', '=', 'b.user_id')
                //->where('a.id', '=', $invoiceh)
                ->whereRaw($where)
                ->select('b.id', 'b.invno', 'b.invdate', 'b.desc', 'b.amount', 'b.filename', 'b.unread', 'b.published', 'c.name', 'c.email')
                ->paginate($perpage);
        }

        if ($log) {
            return InvoiceL::where('invoiceh_id', '=', $log)->get();
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
                'NoOfRec' => 0,
                'TotRec' => 0,

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

        $publish = \Request::get('publish');


        if ($publish) {

            Invoice::where('invoiceh_id', '=', $id)
                ->update(['published' => true]);

            return;
        }

        list($month, $year, $cocode) = explode(',', $id);

        if ($month && $year && $cocode) {
            $dir = storage_path('invoices\\' . $cocode . '\\' . $year . $month . '\\');

            if (is_dir($dir)) {
                $metafile = $dir . 'metadata.json';
                if (file_exists($metafile)) {
                    $metacontent = file_get_contents($metafile);
                    $metadata = json_decode($metacontent, true);


                    if (!isset($metadata['CoCode']) || !isset($metadata['Month']) || !isset($metadata['Year'])) {

                        $this->retStatus('Invalid Metadata', 'Metadata Format Is Invalid');
                    };

                    //return ['msg' => $month !=  $metadata['Month']];

                    if ($cocode != $metadata['CoCode'] || $month != $metadata['Month'] || $year != $metadata['Year']) {

                        $errors['msg'] = ['Metadata Is For Wrong Entity/Period'];
                        $message = ['message' => 'Invalid Metadata', 'errors' => $errors];
                        return response()->json($message, 422);
                    }

                    // Get InvoiceH id
                    $invoiceh = InvoiceH::where('CoCode', '=', $cocode)
                        ->where('year', '=', $year)
                        ->where('month', '=', $month)
                        ->first();

                    Invoice::where('invoiceh_id', '=', $invoiceh->id)
                        ->delete();
                    InvoiceL::where('invoiceh_id', '=', $invoiceh->id)
                        ->delete();

                    if (!isset($invoiceh)) {
                        if ($cocode != $metadata['CoCode'] || $month != $metadata['Month'] || $year != $metadata['Year']) {

                            $errors['msg'] = ['Cannot Find The Header For The Entity/Period'];
                            $message = ['message' => 'Invalid Header', 'errors' => $errors];
                            return response()->json($message, 422);
                        }
                    }

                    //Loop Metadata
                    if (array_key_exists('Items', $metadata)) {

                        $counter = 0;

                        foreach ($metadata['Items'] as $key => $value) {
                            $CustNo = $value['CustNo'];
                            $CustName = $value['CustName'];
                            $Email = $value['Email'];
                            $InvNo = $value['InvNo'];
                            $InvDate = $value['InvDate'];
                            $Desc = $value['Desc'];
                            $Amount = $value['Amount'];
                            $Filename = $value['Filename'];

                            if (file_exists($dir . $Filename)) {
                                $user = User::where('email', '=', $Email)->first();

                                if (isset($user)) {
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
                                    $counter++;
                                } else {
                                    $this->logImport($invoiceh->id, "Can't Find Email : " . $Email);
                                }
                            } else {
                                $this->logImport($invoiceh->id, "Can't Find File : " . $Filename);
                            }
                        }

                        InvoiceH::where('CoCode', '=', $cocode)
                            ->where('year', '=', $year)
                            ->where('month', '=', $month)->update(['NoOfRec' => $counter, 'TotRec' => count($metadata['Items'])]);
                    } else {
                        // No Items in the Metadata                        
                        $errors['msg'] = ['Metadata Has No Items'];
                        $message = ['message' => 'Invalid Metadata', 'errors' => $errors];
                        return response()->json($message, 422);
                    }
                } else {
                    // Metadata Not Found                                      
                    $errors['msg'] = ['Metadata Not Found'];
                    $message = ['message' => 'Invalid Metadata', 'errors' => $errors];
                    return response()->json($message, 422);
                }
            } else {
                // Directory Not Found                                
                $errors['msg'] = ['Path Not Found'];
                $message = ['message' => 'Invalid Path', 'errors' => $errors];
                return response()->json($message, 422);
            }

            return;
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


    private function logImport($id, $text)
    {
        InvoiceL::create([
            'invoiceh_id' => $id,
            'text' => $text
        ]);
    }

    private function retStatus($text, $subtext)
    {
        $errors['msg'] = [$subtext];
        $message = ['message' => $text, 'errors' => $errors];
        return response()->json($message, 422);
    }
}
