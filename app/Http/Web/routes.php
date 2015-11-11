<?php
/**
 * @Author:      Alvin
 * @DateTime:    2015-08-21 16:28:22
 * @Description: 首页
 */
$app->get('/', ['as' => 'dashboard',  'middleware' => 'auth',  'uses' => 'IndexController@main']);

$app->get('test', ['uses' => 'IndexController@test']);

/**
 * @Author:      Alvin
 * @DateTime:    2015-08-21 16:27:28
 * @Description: 登陆与退出
 */
$app->get('auth/login', ['as' => 'getLogin', 'uses' => 'Auth\AuthController@getLogin'])
    ->post('auth/login', ['as' => 'postLogin', 'uses' => 'Auth\AuthController@postLogin'])
    ->get('auth/logout', ['as' => 'getLogout', 'uses' => 'Auth\AuthController@getLogout']);

/**
 * @Author:      Alvin
 * @DateTime:    2015-08-25 15:37:35
 * @Description: 后台管理通用鉴权
 */
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

