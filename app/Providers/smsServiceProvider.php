<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class smsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     * @author LaravelAcademy.org
     */
    public function register()
    {
        //使用singleton绑定单例
        $this->app->singleton('App\Contracts\smsContract',function($app){
            return new smsContract(config('sms'));
        });

        //使用bind绑定实例到接口以便依赖注入
        /*$this->app->bind('App\Contracts\smsContract',function(){
            return new smsService();
        });
        */
    }
}