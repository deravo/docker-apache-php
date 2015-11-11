<?php
namespace App\Http\Man\Controllers\Courses\Subject;

use App;
use Auth;
use App\Models\Man\Manager;
use Illuminate\Http\Request;
use App\Http\Man\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class GetController extends Controller
{
    private $hasher;
    private $auth;
    /**
     * @Author:      Alvin
     * @DateTime:    2015-08-26 10:07:18
     * @Description: Construct
     */
    public function __construct(HasherContract $hasher)
    {
        $this->hasher = $hasher;
        $this->middleware('man.auth');
    }

    /**
     * @Author:      Alvin
     * @DateTime:    2015-08-26 12:46:58
     * @Description: 后台首页
     */
    public function getList(Request $request)
    {
        return view('man.courses.subject.list');
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