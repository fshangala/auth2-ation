<?php
namespace Fshangala\Auth2Ation\Auth;

use Illuminate\Support\ServiceProvider;
use Fshangala\Auth2Ation\Models\Authentication;
use Fshangala\Auth2Ation\Models\User;

class FAuthServiceProvider extends ServiceProvider
{
    public function register()
    {}

    public function boot()
    {
        $this->app['auth']->viaRequest('api',function($request){
            if ($request->header('Authorization')){
                try {
                    $token = $request->header('Authorization');
                    $authentication = Authentication::where('token',$token)->first();
                    if($authentication){
                        $user = User::find($authentication->user_id);
                        return $user;
                    } else {
                        return null;
                    }
                } catch (\Throwable $th) {
                    return null;
                }
            } else {
                return null;
            }
        });
    }
}