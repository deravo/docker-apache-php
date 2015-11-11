<?php namespace App\Http\Api\Controllers\User;

use App;
use DB;
use App\Models\Api\User;
use App\Http\Api\Controllers\Controller;
use Illuminate\Http\Request;

class Update extends Controller
{
    private $redis;
    public function __construct()
    {
        $this->redis = App::make('redis');
    }

    /**
     * @Author:      Alvin
     * @Email:       jin@aliuda.com
     * @DateTime:    2015-10-28 17:46:46
     * @Description: 更新用户基本信息
     */
    public function profile(Request $request)
    {
        $data = $request->only(['realname', 'nickname', 'gender', 'age']);

        $data['gender'] = (int)$data['gender'] != 0 ?:1;
        $data['realname'] = cutstr($data['realname'], 10, false);
        $data['nickname'] = cutstr($data['nickname'], 10, false);
        $data['age'] = ((int)$data['age'] > 18 && (int)$data['age'] < 70) ?: 0;

        $user = User::find((int)$request->header('user-id'));
        $user->gender  = $data['gender'];
        $user->realname = $data['realname'];
        $user->nickname = $data['nickname'];
        $user->age = $data['age'];
        if ( $user->save() )
        {
            $this->redis->connection('logined')->set($user->user_id, json_encode($user));
            return json_output(2000, '更新成功', $user);
        }
        else {
            return json_output(2001, '更新失败');
        }
    }


    public function exam(Request $request)
    {
        $data = $request->only(['province_id', 'district_id', 'exam_type', 'exam_level']);

        $province = DB::select("select district_id, district_name from info_district where district_id in (?, ?)", [(int)$data['province_id'], (int)$data['district_id']]);
        if ( count($province) == 2 )
        {
            $district = $province[1];
            $province = $province[0];
        }
        else {
            return json_output(2001, '报考地区必须选择');
        }

        if ( (int)$data['exam_type'] > 1 || (int)$data['exam_type'] < 0)
        {
            $data['exam_type'] = 0;
        }

        if ( (int)$data['exam_level'] > 2 || (int)$data['exam_level'] < 0 )
        {
            $data['exam_level'] = 0;
        }

        $user = User::find((int)$request->header('user-id'));
        $user->exam_type  = (int)$data['exam_type'];
        $user->exam_level = (int)$data['exam_level'];
        $user->province_id = (int)$data['province_id'];
        $user->district_id = (int)$data['district_id'];
        if ( $user->save() )
        {
            $user->province = $province->district_name;
            $user->district = $district->district_name;
            $this->redis->connection('logined')->set($user->user_id, json_encode($user));
            return json_output(2000, '更新成功', $user);
        }
        else {
            return json_output(2001, '更新失败');
        }
    }

    /**
     * Store a secret message for the user.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function storeSecret(Request $request, $id)
    {
        $user = User::find($id);
        $user->fill([
            'secret' => Crypt::encrypt($request->secret)
        ])->save();
    }
}
