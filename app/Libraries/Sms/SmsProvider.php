<?php
namespace App\Libraries\Sms;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class SmsProvider extends ServiceProvider
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
        $this->app->singleton('SmsContract',
            function($app){
                return new SmsService(config('sms'));
            }
        );
        //使用bind绑定实例到接口以便依赖注入
        $this->app->bind('SmsContract', function($app){
            return new SmsService('sms');
        });
    }
}
