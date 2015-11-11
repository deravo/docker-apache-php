<?php
namespace App\Http\Api\Controllers\User;

use App;
use DB;
use App\Models\Api\User;
use App\Http\Api\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class Get extends Controller
{
    private $redis;
    private $hasher;
    public function __construct(HasherContract $hasher)
    {
        $this->hasher = $hasher;
        $this->redis = App::make('redis');
    }

    /**
     * @Author:      Alvin
     * @Email:       jin@aliuda.com
     * @DateTime:    2015-10-28 18:02:09
     * @Description: 获取用户基本信息
     */
    public function profile(Request $request)
    {
        $profile = json_decode($this->redis->connection('logined')->get($request->header('user-id')));
        if ( $profile->province_id && $profile->district_id )
        {
            if ( !$profile->province || !$profile->district )
            {
                $district = DB::select("select district_name from info_district where district_id in (?, ?)", [$profile->province_id, $profile->district_id]);
                $profile->province = $district[0]->district_name;
                $profile->district = $district[1]->district_name;
                $this->redis->connection('logined')->set($request->header('user-id'), json_encode($profile));
            }
        }
        return json_output(2000, 'SUCCESS', $profile);
    }

}
