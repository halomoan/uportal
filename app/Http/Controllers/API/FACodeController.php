<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;
use App\Models\SAPConfig;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class FACodeController extends Controller
{

    private $MAX_CHUNK = 100;
    private $SAPGROUP = ['1', '2'];
    private $FILESTORE = '/storage/faimages/';
    private $MAX_LIMIT = 10;

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
        $page = \Request::get('page');
        $qtype = \Request::get('qtype');
        $qcocode = \Request::get('cocode');

        if (strlen($qcocode) == 4) {
            $limit = $this->MAX_LIMIT;


            if ($qtype == 'image') {



                $result = $this->getImages($qcocode, $page, $limit);

                if ($result) {
                    return  new Paginator($result['data'], $result['total'], $limit);
                } else {
                    return  new Paginator([], 0, $limit);
                }
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
        $this->validate($request, [
            'serverkey' => 'required',
            'phoneid' => 'required',
            'cocode' => 'required',
            'version' => 'required',
            'deregister' => 'sometimes',
            'codes' => 'sometimes',
            'files' => 'sometimes',
            'files.*' => 'image|mimes:png,jpeg|max:2048'
        ]);

        $phoneid = $request->get('phoneid');
        $serverkey = $request->get('serverkey');
        $version = $request->get('version');
        $cocode = $request->get('cocode');

        //return response(['status' => true, 'msg' => $cocode]);

        $codes = $request->get('codes');

        //return response(['status' => true, 'msg' => $codes[0]]);

        $result = $this->updSAPCode($cocode, $phoneid, $serverkey, $version, $codes);

        if (!$result['status']) {

            return response(['status' => false, 'msg' => $result['msg']]);
        }


        if ($request->hasfile('files')) {

            $path = public_path('/storage/faimages/' . $cocode . '/');

            foreach ($request->file('files') as $image) {
                $name = $image->getClientOriginalName();

                $currentPhoto = $path . $name;
                $prefix = '';
                if (file_exists($currentPhoto)) {
                    //@unlink($currentPhoto);
                    $image->move(
                        $path . '/New/',
                        $name
                    );
                } else {
                    $image->move($path,  $name);
                }

                //\Image::make($request->photo)->save(public_path('storage/faimages/') . $name)->fit(800, 800);


            }
        }

        return response(['status' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($id == 'test') {
            $result = [
                'status' => true,
                'msg' => 'Successfully Connected'
            ];
        } else {
            $group = $this->getSAPGroup();
            if ($group === null) {
                return response(['msg' => 'Phone ID is not part of any group']);
            }
            $result = $this->getFAInfo($group->id, $id);
        }
        return  $result;
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
        $this->validate($request, [
            'keepNew' => 'required',
            'keepCurrent' => 'required'
        ]);

        $keepNew = $request->get('keepNew');
        $keepCurrent = $request->get('keepCurrent');
        $cocode = substr($id, 0, 4);


        if ($keepCurrent) {

            $path = public_path($this->FILESTORE  . $cocode . '/');
            //if (file_exists($path . 'NEW/' . $id . '.png')) {
            unlink($path . 'NEW/' . $id . '.png');
            //}
        }
        if ($keepNew) {
            $path = public_path($this->FILESTORE  . $cocode . '/');
            //if (file_exists($path . 'NEW/' . $id . '.png')) {
            rename($path . 'NEW/' . $id . '.png', $path  . $id . '.png');
            //}
        }
        return (['status' => true, 'msg' => 'OK']);
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

    private function updSAPCode($cocode, $phoneid, $serverkey, $version, $codes)
    {

        // $user = auth('api')->user();
        // $group = $user->groups()->first();
        $group = $this->getSAPGroup();
        if ($group === null) {
            return response(['msg' => 'Phone ID is not part of any group']);
        }

        $XML_ARRAY = array();
        $timestamp = Carbon::now()->format('Ymd');
        $newXML = "";

        if ($codes == null) {
            $totalItems = 0;
        } else {
            $totalItems = count($codes);
        }

        $idx = 0;

        while ($idx < $totalItems) {
            $newXML = "<?xml version='1.0' encoding='UTF-8'?>";
            $newXML = $newXML . "<SCAN_BARCODE>";
            $newXML = $newXML . "<ENTITY timestamp='" . $timestamp . "' uniqueid='" . $phoneid . "' serverkey='" . $serverkey . "' version='" . $version . "'>" . $cocode . "</ENTITY>";
            $newXML = $newXML . "<ITEM>";
            $counter = 0;
            while ($counter < $this->MAX_CHUNK && $idx < $totalItems) {
                $newXML = $newXML . "<CODE>" . $codes[$idx] . "</CODE>";
                $counter = $counter +  1;
                $idx = $idx + 1;
            }
            $newXML = $newXML .  "</ITEM></SCAN_BARCODE>";

            array_push($XML_ARRAY, $newXML);
        }

        //dd($newXML);

        $config = SAPConfig::find($group->id)->toArray();


        try {
            $c = new SapConnection($config);
            $f = $c->getFunction('ZFMB_SETBARCODE');

            for ($i = 0; $i < sizeof($XML_ARRAY); $i++) {
                $xml = $XML_ARRAY[$i];

                $result = $f->invoke([
                    'BARCODE_XML' => $xml
                ]);

                if ($result['TXTRESULT'] != 'OK') {
                    $c->close();
                    return (['status' => false, 'msg' => $result['TXTRESULT']]);
                }
            }

            return (['status' => true, 'msg' => 'OK']);

            $c->close();
        } catch (SapException $ex) {
            //echo 'Exception: ' . $ex->getMessage() . PHP_EOL;
            //$err =  $ex->getErrorInfo();
            //echo $err['code'];
            //echo $err['key'];
            //echo $err['message'];            
            return (['status' => false, 'msg' => 'Cannot Connect SAP']);
        }
    }

    private function getSAPGroup()
    {
        $user = auth('api')->user();
        $group = $user->groups()->whereIn('id', $this->SAPGROUP)->first();


        return $group;
    }

    private function getFAInfo($groupid, $codes)
    {

        $config = SAPConfig::find($groupid)->toArray();

        try {
            $c = new SapConnection($config);

            $f = $c->getFunction('ZFMB_GET_FADESC');
            $result = $f->invoke([
                'BARCODEIDS' => $codes,
            ]);

            $compressed_data = '';
            foreach ($result["CONTENT_BINARY"] as $content) {
                $compressed_data = $compressed_data . $content["LINE"];
            }
            $result = json_decode(gzinflate($compressed_data));
            return $result->result;

            $c->close();
        } catch (SapException $ex) {
            //echo 'Exception: ' . $ex->getMessage() . PHP_EOL;
            //$err =  $ex->getErrorInfo();
            //echo $err['code'];
            //echo $err['key'];
            //echo $err['message'];            
            //return response(['msg' => 'Cannot Connect SAP']);
            return null;
        }
    }

    private function getImages($cocode, $page, $limit)
    {
        $files = Storage::disk('public')->files('faimages/' . $cocode);

        if (!$files) {
            return [];
        }

        $total = count($files);
        $files = array_splice($files, ($page - 1) * $limit, $limit);


        $codes = '';

        foreach ($files as $file) {
            $code = substr($file, 14, 14);
            $codes = $codes . ';' . $code;
        }

        $group = $this->getSAPGroup();

        $infos = $this->getFAInfo($group->id, $codes);

        if ($infos) {

            $path = public_path($this->FILESTORE . $cocode . '/');

            foreach ($infos as $info) {
                $info->url = $this->FILESTORE . $cocode . '/' . $info->id . '.png';

                if (file_exists($path . 'NEW/' . $info->id . '.png')) {
                    $info->hasNew = true;
                    $info->urlNew = $this->FILESTORE . $cocode . '/NEW/' . $info->id . '.png';
                } else {
                    $info->hasNew = false;
                }
            }
            return ['total' => $total, 'data' => $infos];
        } else {
            return ['total' => 0, 'data' => []];
        }
    }
}
