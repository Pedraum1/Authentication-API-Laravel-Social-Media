<?php

namespace App\Classes;

use App\Models\ImageModel;
use App\Models\UserModel;
use App\Rules\ProfileNameLength;
use App\Rules\BannerNameLength;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfileClass{

  public static function profileImage(UserModel $user): string{
    if($user->profile_image != null){
      return $user->profile_image->name;
    }
    return 'noProfile.webp';
  }

  public static function bannerImage(UserModel $user): string{
    if($user->banner_image != null){
      return $user->banner_image->name;
    }
    return 'noProfile.webp';
  }

  public static function organizeProfileData(UserModel $user){

    $profile_image = ProfileClass::profileImage($user);
    $banner_image = ProfileClass::bannerImage($user);
        return [
          'username'=>$user->username,
          'description'=>$user->description,
          'tag'=>$user->tag,
          'followers_num'=>$user->followersNumber,
          'following_num'=>$user->followingNumber,
          'posts_num'=>$user->postsNumber,
          'profile_img'=>$profile_image,
          'banner_img'=>$banner_image,
          'posts'=>$user->posts
        ];
  }
}