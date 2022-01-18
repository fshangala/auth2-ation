<?php
namespace Fshangala\Auth2Ation\Auth;
use Fshangala\Auth2Ation\Models\Authentication;
use App\Models\User;

class FAuthentication
{
    public function authenticate($request){
        if ($request->header('Authorization')){
            $token = $request->header('Authorization');
            $authentication = Authentication::where('token',$token)->first();
            if($authentication){
                $user = User::find($authentication->user_id);
                return $user;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}
