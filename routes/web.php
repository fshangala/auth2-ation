<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace'=>'Fshangala\Auth2Ation\Http\Controllers'
],function($router){
    $router->group(['prefix'=>'/auth'],function($router){
        $router->post('/register','UserController@register');
        $router->post('/create-superuser','UserController@createSuperUser');
        $router->post('/login','UserController@login');
        $router->post('/add-permission','AuthorizationController@addPermission');
        $router->delete('/delete-permission','AuthorizationController@deletePermission');
        $router->group(['prefix'=>'/user'],function($router){
            $router->get('/','UserController@user');
            $router->get('/all','UserController@all');
            $router->group(['prefix'=>'/{username}'],function($router){
                $router->get('/','UserController@getUser');
            });
        });
    });
});