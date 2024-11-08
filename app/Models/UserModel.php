<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserModel extends Model
{

    protected $table = 'users';

    /**
     * use the parameter $option as False|Null or True to change between
     * returning if login exists or returning a user object respectively.
     *
     * @param  string $email
     * @param  string $password
     * @param  bool $option
     * @return true|false|UserModel
     */
    public static function getLoginOrUser($email,$password,$option=False){
        $possible_user = UserModel::where('deleted_at',null)->where('email',$email)->first();
        if(empty($possible_user)){
            return False;
        }
        if(!password_verify($password, $possible_user->password)){
            return False;
        }
        if($option == True){
            if($possible_user->email_verified_at != null){
                return $possible_user;
            }
            return False;
        }
        return True;
    }

    public static function getAliveUser(){
        return UserModel::where('deleted_at',null)->where('active',1)->whereNotNull('email_verified_at')->get();
    }

    public static function getUserByTag(string $tag){
        return UserModel::getAliveUser()->where('tag',$tag)->first();
    }

    public function profile_image(): HasOne {
        return $this->hasOne(ImageModel::class,'source_id','id')->where('deleted_at',null)->where('type','profile');
    }
}
