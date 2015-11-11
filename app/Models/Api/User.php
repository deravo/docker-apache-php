<?php namespace App\Models\Api;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'info_user';

    protected $primaryKey = 'user_id';

    const UPDATED_AT = 'last_activity_time';
    const CREATED_AT = 'created_time';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = 'all'; //['username', 'password', 'realname', 'telephone', 'nickname', 'gener'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

//    protected $updatable = ['update_time'];


    public function getUserByTelephone($query, $telephone)
    {
        return $query->where('telephone', '=', $telephone);
    }

    
}
