<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace'=>'Fshangala\Auth2Ation\Http\Controllers'
],function($router){
    $router->group(['prefix'=>'/auth'],function($router){
        $router->post('/register','UserController@register');
        $router->post('/login','UserController@login');
    });
    $router->get('/user','UserController@user');
});