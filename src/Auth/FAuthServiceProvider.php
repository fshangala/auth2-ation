<?php
namespace Fshangala\Auth2Ation\Auth;

use Exception;
use Illuminate\Support\ServiceProvider;
use Fshangala\Auth2Ation\Models\Authentication;
use Fshangala\Auth2Ation\Models\User;
use Fshangala\Auth2Ation\Models\Authorization;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;

class FAuthServiceProvider extends ServiceProvider
{
    public function register()
    {}

    public function boot()
    {
        Gate::define('create-superuser',function($user=null){
            $superuser = User::where('is_superuser',true)->first();
            if($superuser){
                return Response::deny('Superuser already exists.');
            } else {
                return Response::allow();
            }
        });
        Gate::define('permission',function($user,$fields){
            if($user->is_superuser){
                return Response::allow();
            }
            $permission = Authorization::where('user_id',$user->id)->where('action',$fields['action'])->where('resource',$fields['resource']);
            if(array_key_exists('target',$fields)){
                $permission1 = clone $permission;
                $targetCheck = $permission1->where('target',$fields['target'])->firstOr(function(){
                    return false;
                });
                if($targetCheck){
                    if($targetCheck->grant){
                        return Response::allow();
                    } else {
                        return Response::deny('Not enough privilages!1');
                    }
                }
            }
            if (array_key_exists('type',$fields)){
                $permission2 = clone $permission;
                $typeCheck = $permission2->where('type',$fields['type'])->firstOr(function(){
                    return false;
                });
                if($typeCheck){
                    if($typeCheck->grant){
                        return Response::allow();
                    } else {
                        return Response::deny('Not enough privilages!2');
                    }
                }
            }
            
            $resource = $permission->first();
            if($resource){
                if($resource->grant){
                    return Response::allow();
                } else {
                    return Response::deny('Not enough privilages!3');
                }
            } else {
                return Response::deny('Not enough privilages!4'.json_encode($resource));
            }
        });
        $this->app['auth']->viaRequest('api',function($request){
            if ($request->header('Authorization')){
                try {
                    $token_str = $request->header('Authorization');
                    $token_arr = explode(';',$token_str);
                    $authentication = Authentication::where('token',$token_arr[1])->first();
                    if($authentication){
                        if($token_arr[0] == 'bearer'){
                            $user = User::find($authentication->user_id);
                        }
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