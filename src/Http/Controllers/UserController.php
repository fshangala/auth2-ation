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
        $res = null;
        $statusCode = 500;

        $users = User::all();
        $res=$users;
        $statusCode=200;

        return response($res, $statusCode);
    }

    public function register(Request $request)
    {
        $res = null;
        $statusCode = 500;

        $rules = [
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
         ];
 
        $customMessages = [
             'required' => 'Please fill attribute :attribute'
        ];
        $this->validate($request, $rules);
 
        try {
            $hasher = app()->make('hash');
            $username = $request->input('username');
            $email = $request->input('email');
            $password = $hasher->make($request->input('password'));
 
            $save = User::create([
                'username'=> $username,
                'email'=> $email,
                'password'=> $password
            ]);
            
            $res = $save;
            $statusCode = 200;
        } catch (\Illuminate\Database\QueryException $ex) {
            $res = $ex->getMessage();
        }

        return response($res, $statusCode);
    }

    public function login(Request $request)
    {
        $res = null;
        $statusCode = 500;
         
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];
        $this->validate($request, $rules);

        $email = $request->input('email');
        try {
            $login = User::where('email', $email)->firstOrFail();
            if ($login) {
                if ($login->count() > 0) {
                    if (Hash::check($request->input('password'), $login->password)) {
                        try {
                            $api_token = sha1($login->id.time());
                            $authorization = Authentication::create([
                                "user_id"=>$login->id,
                                "token"=>$api_token
                            ]);
                            $res = $authorization;
                            $statusCode = 200; 
 
                        } catch (\Illuminate\Database\QueryException $ex) {
                            
                            $res = $ex->getMessage();
                        }
                    } else {
                        $statusCode = 401;
                    }
                } else {
                    $statusCode = 401;
                }
            } else {
                $statusCode = 401;
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $res = $ex->getMessage();
            $statusCode = 500;
        }

        return response($res, $statusCode);
    }
}
