<?php
require_once __DIR__ . '/../vendor/autoload.php';

//~ 简单加载配置模式，读取根目录下的 .env 文件
Dotenv::load(__DIR__ . '/../');

//~ 当前采用 完全加载模式，和 laravel 5 一样，读取 ./config/ 目录下 所有文件
//~ 加载自定义配置的方法，文件必须放在 ./config 目录下
//~ $app->configure('options');

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);
$app->configure('sms');
$app->configure('session');
//$app->configure('sentinel');
//~ 加载扩展接口类
$app->withFacades();
//~ 加载强大的Eloquent
$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
*/
$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);
$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
*/
$app->register(
    App\Providers\AppServiceProvider::class,
    App\Providers\EventServiceProvider::class
//  这个注入方法没成功，后面再测试，先用下面的bind方法
//    ,App\Libraries\Sms\SmsProvider::class
);


//自定义功能扩展
//~ 短信发送类
$app->bind('App\Libraries\Sms\SmsContract', 'App\Libraries\Sms\SmsService');

$app->configure('twigbridge');
$app->register('TwigBridge\ServiceProvider');

define('BASE_NAMESPACE', 'App\Http\Error\Controllers');
define('API_NAMESPACE', 'App\Http\Api\Controllers');
define('WEB_NAMESPACE', 'App\Http\Web\Controllers');
define('MAN_NAMESPACE', 'App\Http\Man\Controllers');


switch(strtolower(explode(".", $app->request->getHost())[0]))
{
    case 'api':
        $app->middleware([
            Illuminate\Cookie\Middleware\EncryptCookies::class,
            Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            Illuminate\Session\Middleware\StartSession::class,
            Illuminate\View\Middleware\ShareErrorsFromSession::class,
            Vluzrmos\LumenCors\CorsMiddleware::class
        ]);
        $app->routeMiddleware([
            'api.auth'              => 'App\Http\Api\Middleware\Authenticate',
            'api.response'          => 'App\Http\Api\Middleware\Response',
            'api.guest'             => 'App\Http\Api\Middleware\RedirectIfAuthed'
        ]);
        $app->group(
            [
                'namespace'     => API_NAMESPACE
            ],
            function ($app) {
                require __DIR__.'/../app/Http/Api/routes.php';
            }
        );
        break;
    case 'man':
        $app->middleware([
            Illuminate\Cookie\Middleware\EncryptCookies::class,
            Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            Illuminate\Session\Middleware\StartSession::class,
            Illuminate\View\Middleware\ShareErrorsFromSession::class,
            App\Http\Man\Middleware\VerifyCsrfToken::class,
            Vluzrmos\LumenCors\CorsMiddleware::class
        ]);
        $app->routeMiddleware([
           'man.auth'              => 'App\Http\Man\Middleware\Authenticate',
           'man.guest'             => 'App\Http\Man\Middleware\RedirectIfAuthed'
        ]);
        $app->group(
            [
                'namespace' => MAN_NAMESPACE
            ],
            function ($app) {
                require __DIR__.'/../app/Http/Man/routes.php';
            }
        );
        break;
    default:
        $app->middleware([
            Illuminate\Cookie\Middleware\EncryptCookies::class,
            Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            Illuminate\Session\Middleware\StartSession::class,
            Illuminate\View\Middleware\ShareErrorsFromSession::class,
            App\Http\Man\Middleware\VerifyCsrfToken::class,
            Vluzrmos\LumenCors\CorsMiddleware::class
        ]);
        $app->routeMiddleware([
            'web.auth'              => 'App\Http\Web\Middleware\Authenticate',
            'web.guest'             => 'App\Http\Web\Middleware\RedirectIfAuthed'
        ]);
        $app->group(
            [
                'namespace' => WEB_NAMESPACE
            ],
            function ($app) {
                require __DIR__.'/../app/Http/Web/routes.php';
            }
        );
        break;
}
/*
$app->group(
    [
        'namespace' => BASE_NAMESPACE
    ],
    function ($app)
    {
        require __DIR__ . '/../app/Http/Error/routes.php';
    }
);
*/
return $app;
