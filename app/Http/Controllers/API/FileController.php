<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection\Excel;

class FileController extends Controller
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
        $filecat = $request->input('fcat');
        $file = $request->file('file');
        // File Details 
        //$filename = $file->getClientOriginalName();
        $filename = time() . $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();

        // 2MB in Bytes
        $maxFileSize = 2097152;

        // Valid File Extensions
        $valid_extension = array("csv");

        // Check file extension
        if (in_array(strtolower($extension), $valid_extension)) {
            // Check file size
            if ($fileSize <= $maxFileSize) {
                // File upload location
                Storage::disk('adminfolder')->putFileAs(
                    'uploadfiles/',
                    $file,
                    $filename
                );

                //$contents = Storage::disk('adminfolder')->get('uploadfiles/' . $filename);

                if ($filecat == 'users') {

                    $status = $this->uploadUsers(storage_path("admfolder\\uploadfiles\\$filename"));
                    return $status;
                } else {

                    return "ABC" . $filecat;
                }
            } else {
                return ['msg' => 'Size is Not OK'];
            }
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
        $filename = null;
        if ($id === 'users') {
            $filename = $this->getUsers();
        }

        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'users.csv', $headers);
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

    private function getUsers()
    {
        $table =  DB::table('users as a')
            ->select('a.*', 'b.id as groupid', 'b.name as group')
            ->leftJoin('group_user as c', 'a.id', '=', 'c.user_id')
            ->leftJoin('groups as b', 'c.group_id', '=', 'b.id')
            ->orderBy('a.id', 'asc')->get();

        $filename = "users.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, array('id', 'name', 'email', 'company', 'groupid', 'group', 'created at', 'todelete'));

        foreach ($table as $row) {
            fputcsv($handle, array($row->id, $row->name, $row->email, $row->company, $row->groupid, $row->group, $row->created_at, ''));
        }
        fclose($handle);

        return $filename;
    }

    private function uploadUsers($filename)
    {
        $col = ['id' => 0, 'name' => 1, 'email' => 2, 'company' => 3, 'groupid' => 4, 'group' => 5, 'created_at' => 6, 'todelete' => 7];
        $handle = fopen($filename, "r");
        $header = true;


        while ($csvLine = fgetcsv($handle, 1000, ",")) {

            if ($header) {
                $header = false;
            } else {

                if ($csvLine[$col['todelete']] == 'Y') {
                } else {
                };
            }
        }
    }
}
