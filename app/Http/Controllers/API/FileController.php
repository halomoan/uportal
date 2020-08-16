<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Group;
use App\Models\Logger;

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
        $logger = \Request::get('logger');

        if ($logger) {
            $user = auth('api')->user();
            return Logger::where('user_id', '=', $user->id)
                ->where('category', '=', $logger)->get();
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
        $filemode = $request->input('fmode');
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

                    $status = $this->uploadUsers(storage_path("admfolder\\uploadfiles\\$filename"), $filemode);
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
            ->where('urole', '<>', 'admin')
            ->orderBy('a.id', 'asc')->get();

        $filename = "users.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, array('name', 'email', 'company', 'groupid', 'group', 'created at', 'password', 'todelete(Y)', 'tomodify(Y)', 'id'), chr(9));

        foreach ($table as $row) {
            fputcsv($handle, array($row->name, $row->email, $row->company, $row->groupid, $row->group, $row->created_at, '', '', '', $row->id), chr(9));
        }
        fclose($handle);

        return $filename;
    }

    private function uploadUsers($filename, $mode)
    {
        $col = ['name' => 0, 'email' => 1, 'company' => 2, 'groupid' => 3, 'group' => 4, 'created_at' => 5, 'password' => 6, 'todelete' => 7, 'tomodify' => 8, 'id' => 9];
        $handle = fopen($filename, "r");
        $header = true;

        $userid = auth('api')->user()->id;
        Logger::where('user_id', '=', $userid)->where('category', '=', 'users')->delete();

        if ($mode === 'clear') {
            $res =  User::where('urole', '=', 'user')->delete();
            if (!$res) {
                $logtext = 'Failed to clear all the users';
                Logger::create([
                    'user_id' => $userid,
                    'category' => 'users',
                    'text' => $logtext
                ]);
                return;
            }
        }

        while ($csvLine = fgetcsv($handle, 1000, chr(9))) {

            if ($header) {
                $header = false;
            } else {

                if ($csvLine[$col['todelete']] == "Y") {
                    if ($mode == 'append') {
                        if ($csvLine[$col['id']]) {
                            $user = User::find($csvLine[$col['id']]);
                            if ($user) {
                                $user->delete();
                            } else {
                                $logtext = 'ID : ' . $csvLine[$col['id']] . ' not found, cannot delete user ' . $csvLine[$col['name']];
                                Logger::create([
                                    'user_id' => $userid,
                                    'category' => 'users',
                                    'text' => $logtext
                                ]);
                            }
                        } else {
                            //Logger
                            $logtext = 'ID not found, cannot delete user ' . $csvLine[$col['name']];
                            Logger::create([
                                'user_id' => $userid,
                                'category' => 'users',
                                'text' => $logtext
                            ]);
                        }
                    }
                } elseif ($csvLine[$col['tomodify']] == "Y") {
                    if ($mode == 'append') {
                        if ($csvLine[$col['id']]) {
                            $user = User::find($csvLine[$col['id']]);
                            if ($user) {
                                if (strlen($csvLine[$col['name']]) > 0) {
                                    $user->name = $csvLine[$col['name']];
                                }
                                if (strlen($csvLine[$col['email']]) > 0) {
                                    $user->email = $csvLine[$col['email']];
                                }
                                if (strlen($csvLine[$col['company']]) > 0) {
                                    $user->company = $csvLine[$col['company']];
                                }
                                if (strlen($csvLine[$col['password']]) > 0) {
                                    $user->password = Hash::make($csvLine[$col['password']]);
                                }
                                try {
                                    $user->save();
                                } catch (\Exception $e) {
                                    $logtext = $e->getMessage();
                                    Logger::create([
                                        'user_id' => $userid,
                                        'category' => 'users',
                                        'text' => $logtext
                                    ]);
                                }
                            } else {
                                $logtext = 'ID : ' . $csvLine[$col['id']] . ' not found, cannot modify user ' . $csvLine[$col['name']];
                                Logger::create([
                                    'user_id' => $userid,
                                    'category' => 'users',
                                    'text' => $logtext
                                ]);
                            }
                        } else {
                            //Logger
                            $logtext = 'ID not found, cannot modify user ' . $csvLine[$col['name']];
                            Logger::create([
                                'user_id' => $userid,
                                'category' => 'users',
                                'text' => $logtext
                            ]);
                        }
                    }
                } else {

                    try {
                        $user = User::create([
                            "name" => $csvLine[$col['name']],
                            "email" => $csvLine[$col['email']],
                            "type" => 'person',
                            "urole" => 'user',
                            "company" => $csvLine[$col['company']],
                            "password" => Hash::make($csvLine[$col['password']]),

                        ]);
                        $groups = Group::find($csvLine[$col['groupid']]);
                        if ($groups) {
                            $user->groups()->sync($groups);
                        } else {
                            //Logger
                            $logtext = 'Group ID not found, assign group for user ' . $csvLine[$col['name']];
                            Logger::create([
                                'user_id' => $userid,
                                'category' => 'users',
                                'text' => $logtext
                            ]);
                        }
                    } catch (\Illuminate\Database\QueryException $e) {

                        $logtext = $e->getMessage();
                        $pos1 = strpos($logtext, 'violation:');
                        $pos2 = strpos($logtext, '(SQL:');
                        $logtext = substr($logtext, $pos1 + 11, $pos2 - ($pos1 + 11));
                        $logtext = $this->truncate($logtext, 188);
                        Logger::create([
                            'user_id' => $userid,
                            'category' => 'users',
                            'text' => $logtext
                        ]);
                    } catch (\Exception $e) {
                        $logtext = $e->getMessage();
                        Logger::create([
                            'user_id' => $userid,
                            'category' => 'users',
                            'text' => $logtext
                        ]);
                    }
                }
            }
        }
        fclose($handle);
    }

    function truncate($string, $length)
    {
        if (strlen($string) > $length) {
            $string = substr($string, 0, $length) . '...';
        }

        return $string;
    }
}
