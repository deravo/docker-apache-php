<?php
/**
 * @Author:      Alvin
 * @Email:       jin@aliuda.com
 * @DateTime:    2015-10-31 14:52:01
 * @Description: 默认路由返回结果
 */
$app->get('/', function(){
    if ( config('app.debug') )
    {
        return view('api.unit_test');
    }
    return json_output(911, 'Invalid Access!');
});

$app->group(
    [
        'prefix'    => 'test',
        'namespace' => API_NAMESPACE
    ],
    function ($app) {
        $app->get('/', ['uses' => 'IndexController@test'])
            ->get('/district', ['uses' => 'IndexController@getDistrict']);
    }
);

/**
 * @Author:      Alvin
 * @Email:       jin@aliuda.com
 * @DateTime:    2015-10-31 14:52:14
 * @Description: 用户注册
 */
$app->group(
    [
        'prefix' => 'auth/register',
        'namespace' => API_NAMESPACE . '\Auth',
        'middleware'    => 'api.response'
    ],
    function($app)
    {
        $CtrlRegistration = 'RegisterController';
        $app->post('/check_phone', ['uses' => $CtrlRegistration . '@postCheckPhone'])
            ->post('/verify_phone', ['uses' => $CtrlRegistration . '@postVerifyCode'])
            ->post('/set_password', ['uses' => $CtrlRegistration . '@postSetPassword']);
    }
);

/**
 * @Author:      Alvin
 * @Email:       jin@aliuda.com
 * @DateTime:    2015-10-31 14:52:25
 * @Description: 用户登录/退出 
 */

$app->group(
    [
        'prefix' => 'auth',
        'namespace' => API_NAMESPACE . '\Auth',
        'middleware' => 'api.response'
    ],
    function($app)
    {
        $CtrlAuth = 'AuthController';
        $app->post('/login', ['as' => 'postLogin', 'middleware' => 'api.guest', 'uses' => $CtrlAuth . '@postLogin'])
            ->post('/logout', ['as' => 'postLogout', 'uses' => $CtrlAuth . '@getLogout'])
            ->get('/logout', ['as' => 'postLogout', 'uses' => $CtrlAuth . '@getLogout'])
            ->post('/modify_password', ['as' => 'postPassword', 'middleware' => 'api.auth', 'uses' => $CtrlAuth . '@postModifyPassword']);
    }
);

/**
 * @Author:      Alvin
 * @Email:       jin@aliuda.com
 * @DateTime:    2015-10-31 14:52:36
 * @Description: 用户信息更新与获取
 */
$app->group(
    [
        'prefix'    => 'user',
        'namespace' => API_NAMESPACE . '\User',
        'middleware'    =>  'api.response'
    ],
    function($app)
    {
        $app->get('/get', ['middleware' => 'api.auth', 'uses' => 'Get@profile'])
            ->post('/update/profile', ['middleware' => 'api.auth', 'uses' => 'Update@profile'])
            ->post('/update/exam', ['middleware' => 'api.auth', 'uses' => 'Update@exam']);
    }
);






/*
2015/10/20 14:42:10
$app->group([
    'prefix' => 'api',
    'namespace' => 'Api'
], function($app){
    $app->post('/register/check', ['uses' => 'Api\RegisterController@postCheck']);
    $app->post('/register/save', ['uses' => 'Api\RegisterController@postSave']);
});
//注册第一步，检查电话号码并发送验证码

$app->get('test', ['uses' => 'IndexController@test']);

/**
 * @Author:      Alvin
 * @DateTime:    2015-08-21 16:27:28
 * @Description: 登陆与退出
 */

/*
2015/10/20 14:42:00
$app->get('auth/login', ['as' => 'getLogin', 'uses' => 'Auth\AuthController@getLogin'])
    ->post('auth/login', ['as' => 'postLogin', 'uses' => 'Auth\AuthController@postLogin'])
    ->get('auth/logout', ['as' => 'getLogout', 'uses' => 'Auth\AuthController@getLogout']);
*/

/**
 * @Author:      Alvin
 * @DateTime:    
 * @Description: 后台管理通用鉴权
 */
/*2015/10/20 14:41:47
$app->group(
    [
        'prefix' => 'admin',
        'namespace' => 'Admin'
        //,'middleware' => 'auth:master'
    ], function($app) {
        $app->get('/', 'AdminHomeController@index');
    }
);

$app->group([
    'prefix' => 'department',
    'namespace' => BASE_NAMESPACE . '\Department'
    ], function($app) {
        $app->get('/', 'Index@index');
});


//~ 变量引用示例
$app->get('posts/{post}/comments/{comment}', function ($postId, $commentPage) {
    return 'You\'re requring the ' . $postId . ' post\'s comments in page ' . $commentPage;
});
*/

/*
$app->get('user/profile', ['as' => 'profile', function () {
    //return ' Show The Profile Of User';
    //~ 下面的代码自动输出HTML跳转Meta信息
    //~ $redirect = redirect()->route('profile');
    $url = route('profile');
    return $url;
}]);
*/
/*
$app->get('user/{id}/profile', ['as' => 'profile', function ($id) {
    //~ show the #{id} user's profile
    $url = route('profile', ['user_id' => $id]);
    return $url;
}]);
*/
/*
$app->get('user/{user_id:\d+}/showProfile', [
    'as' => 'profile', 'uses' => 'UserController@showProfile'
]);
*/
/*
$app->get('user/{id:\d+}/showProfile', function($id) {
    $result = DB::select("select id, content from article");
    $tmp = [];
    foreach($result as $k => $v)
    {
        array_push($tmp, array($v->id, unserialize($v->content)));
    }
    $result = null;
    return $tmp;
});
*/
/*
$app->get('user/{name:[A-Za-z]+}', function ($name) {
    return 'Show User : ' . $name;
});

$app->get('user/{id}', function($id){
    return csrf_token() . '<hr />User : ' . $id;
});
*/

/*
$app->group(['middleware' => 'RoleCheck'], function () use ($app) {
    $app->get('user/list', function () {
        return "Uses Auth Middleware";
    });

    $app->get('user/profile', function () {
        return "Uses Auth Middleware";
    });
});
*/


/*
$app->group(['prefix' => 'admin'], function ($app) {
    $app->get('users', function ()  {
        // Matches The "/admin/users" URL
        return "hope users";
    });
});

$app->group(['prefix' => 'accounts/{account_id}'], function ($app) {
    $app->get('detail', function ($account_id)  {
        // Matches The accounts/{account_id}/detail URL
        $url = url('accounts/' . $account_id . '/detail');
        return $url;
    });
});
*/
