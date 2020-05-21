<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Intervetion\Image\Facades\Image;

class ProfileController extends Controller
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
        return auth('api')->user();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $user = auth('api')->user();

        $this->validate($request, [            
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'curpassword' => 'sometimes|string|min:8|max:191',
            'password' => 'sometimes|string|min:8|max:191',
            'repassword' => 'sometimes|string|min:8|max:191',
            'photo' => 'sometimes|string|nullable'

        ]);

        if (self::is_base64($request->photo)) {

            
            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            \Image::make($request->photo)->save(public_path('storage/').$name)->fit(1200,1200);
            $request->merge(['photo' => $name]);
            
        };

        if (!empty($request->password) && !empty($request->curpassword) && !empty($request->repassword)){
            $request->merge(['password' => Hash::make($request->password)]);

           
            $errors['password'] = ['Required Password'];
            $errors['repassword'] = ['Not match with the new password'];
            $errors['curpassword'] = ['Wrong Password'];
            
            
            $message = ['message'=> 'Password does not match','errors' => $errors ];

            return response()->json($message, 422);
        }
        $user->update($request->all());

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

    private static function is_base64($s)
    {
          return (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s);
    }
}
