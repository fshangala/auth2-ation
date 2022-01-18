<?php
namespace Fshangala\Auth2Ation;
use Fshangala\Auth2Ation\Models\Authentication as AuthenticationModel;
use App\Models\User;

class FAuthentication
{
    public function authenticate($request){
        if ($request->header('Authorization')){
            $token = $request->header('Authorization');
            $authentication = AuthenticationModel::where('token',$token)->first();
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
