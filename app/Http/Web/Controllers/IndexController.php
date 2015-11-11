<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use App\Libraries\Role;

//~ 两个加密类
//~ 1st 单向加密
//~ 2nd 双向加解密
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Crypt;


class IndexController extends Controller
{
    private $hasher;

    private $auth;
    /**
     * @Author:      Alvin
     * @DateTime:    2015-08-26 10:07:18
     * @Description: Construct
     */
    public function __construct(HasherContract $hasher, Guard $auth)
    {
        $this->hasher = $hasher;
        $this->auth = $auth;
    }

    /**
     * @Author:      Alvin
     * @DateTime:    2015-08-26 12:46:58
     * @Description: 后台首页
     */
    public function main()
    {
        $user = $this->auth->user();
        return view('index')->withUser($user);
    }

    /**
     * @Author:      name
     * @DateTime:    2015-08-26 12:46:38
     * @Description: Description
     */
    public function test()
    {
        return $this->hasher->make('123456');
    }
}