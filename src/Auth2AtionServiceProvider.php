<?php
namespace Fshangala\Auth2Ation;
use Illuminate\Support\ServiceProvider;

class Auth2AtionServiceProvider extends ServiceProvider
{
    public function register()
    {}

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}