<?php
namespace Fshangala\Auth2Ation;

use Illuminate\Support\ServiceProvider;
use Fshangala\Auth2Ation\Http\Middleware\Authenticate as AuthenticateMiddleware;

class Auth2AtionServiceProvider extends ServiceProvider
{
    public function register()
    {}

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}