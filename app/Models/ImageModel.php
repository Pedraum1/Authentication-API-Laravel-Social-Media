<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageModel extends Model
{

  protected $table = 'images';

  private static function createImage(Request $request, $source_id, $type){
    if($type=='profile'){
      $image = new ImageModel();

      $new_image = $request->file('profileInput');
      $new_name = Carbon::now()->format('Y-m-d_H-i-s').'-'.$new_image->getClientOriginalName();
      $new_image->storeAs('images',$new_name,'public');
      
      $image->source_id = $source_id;
      $image->type = $type;
      $image->name = $new_name;
      $image->created_at = Carbon::now();
      $image->save();
    }
    if($type='banner'){
      $image = new ImageModel();

      $new_image = $request->file('bannerInput');
      $new_name = Carbon::now()->format('Y-m-d_H-i-s').'-'.$new_image->getClientOriginalName();
      $new_image->storeAs('images',$new_name,'public');
      
      $image->source_id = $source_id;
      $image->type = $type;
      $image->name = $new_name;
      $image->created_at = Carbon::now();
      $image->save();
    }
  }

  private static function updateImage(Request $request, $id,$type){
    $image = ImageModel::find($id);
    if($type=='profile'){

      $old_image_path = 'images/'.$image->name;
      if(Storage::disk('public')->exists($old_image_path)){
        Storage::disk('public')->delete($old_image_path);
      }

      $new_image = $request->file('profileInput');
      $new_name = Carbon::now()->format('Y-m-d_H-i-s').'-'.$new_image->getClientOriginalName();
      $new_image->storeAs('images',$new_name,'public');

      $image->name = $new_name;
      $image->updated_at = Carbon::now();
      $image->save();
    }
    if($type=='banner'){
      $old_image_path = 'images/'.$image->name;
      if(Storage::disk('public')->exists($old_image_path)){
        Storage::disk('public')->delete($old_image_path);
      }

      $new_image = $request->file('bannerInput');
      $new_name = Carbon::now()->format('Y-m-d_H-i-s').'-'.$new_image->getClientOriginalName();
      $new_image->storeAs('images',$new_name,'public');

      $image->name = $new_name;
      $image->updated_at = Carbon::now();
      $image->save();
    }

  }

  public static function processProfileImageUpdate(Request $request, UserModel $user){
    if($request->file('profileInput')){
      if(!empty($user->profile_image)){
        ImageModel::updateImage($request, $user->profile_image->id,'profile');
      } else {
        ImageModel::createImage($request,$user->id,'profile');
      }
    }
  }

  public static function processBannerImageUpdate(Request $request, UserModel $user){
    if($request->file('bannerInput')){
      if(!empty($user->banner_image)){
        ImageModel::updateImage($request, $user->banner_image->id,'banner');
      } else {
        ImageModel::createImage($request,$user->id,'banner');
      }
    }
  }
}
