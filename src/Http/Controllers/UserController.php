<?php
namespace Fshangala\Auth2Ation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

use Fshangala\Auth2Ation\Models\User;
use Fshangala\Auth2Ation\Models\Authentication;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware("auth", ["except"=>[
            "register",
            "login"
        ]]);
    }

    public function user(Request $request)
    {
        $res = null;
        $statusCode = 500;

        $res = $request->user();
        $statusCode = 200;

        return response($res, $statusCode);
    }

    public function all(Request $request)
    {
        $res = ["success"=>false, "message"=>"", "data"=>null];
        $statusCode = 500;

        $users = User::all();
        $res["success"]=true;
        $res["data"]=$users;
        $statusCode=200;

        return response($res, $statusCode);
    }

    public function register(Request $request)
    {
        $res = ["success"=>false, "message"=>"", "data"=>null];
        $statusCode = 500;

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'password' => 'required',
         ];
 
        $customMessages = [
             'required' => 'Please fill attribute :attribute'
        ];
        $this->validate($request, $rules);
 
        try {
            $hasher = app()->make('hash');
            $first_name = $request->input('first_name');
            $last_name = $request->input('last_name');
            $email = $request->input('email');
            $password = $hasher->make($request->input('password'));
 
            $save = User::create([
                'first_name'=> $first_name,
                'last_name'=> $last_name,
                'email'=> $email,
                'password'=> $password
            ]);

            $res['success'] = true;
            $res['message'] = 'Registration success!';
            $res['data'] = $save;
            $statusCode = 200;
        } catch (\Illuminate\Database\QueryException $ex) {
            $res['success'] = false;
            $res['message'] = $ex->getMessage();
        }

        return response($res, $statusCode);
    }

    public function login(Request $request)
    {
        $res = ["success"=>false, "message"=>"", "data"=>null];
        $statusCode = 500;
         
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];
        $customMessages = [
           'required' => ':attribute tidak boleh kosong'
        ];
        $this->validate($request, $rules, $customMessages);

        $email = $request->input('email');
        try {
            $login = User::where('email', $email)->first();
            if ($login) {
                if ($login->count() > 0) {
                    if (Hash::check($request->input('password'), $login->password)) {
                        try {
                            $api_token = sha1($login->id.time());
                            $authorization = Authentication::create([
                                "user_id"=>$login->id,
                                "token"=>$api_token
                            ]);
                            $res['success'] = true;
                            $res['message'] = "Success.";
                            $res['data'] = $authorization;
                            $statusCode = 200; 
 
                        } catch (\Illuminate\Database\QueryException $ex) {
                            $res['success'] = false;
                            $res['message'] = $ex->getMessage();
                        }
                    } else {
                        $res['success'] = false;
                        $res['message'] = 'email and password combination dont match any account in the system';
                        $statusCode = 401;
                    }
                } else {
                    $res['success'] = false;
                    $res['message'] = 'email and password combination dont match any account in the system';
                    $statusCode = 401;
                }
            } else {
                $res['success'] = false;
                $res['message'] = 'email and password combination dont match any account in the system';
                $statusCode = 401;
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $res['success'] = false;
            $res['message'] = $ex->getMessage();
            $statusCode = 500;
        }

        return response($res, $statusCode);
    }
}
