<?php

namespace App\Classes;

use App\Mail\RegisterEmailConfirmation;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthClass {
  public static function validateLogin(Request $request){

    $request->validate([
          'emailInput'    => 'required|email',
          'passwordInput' => 'required',
      ],[
          'emailInput.required'    => 'Insira seu Email para realizar login',
          'emailInput.email'       => 'O Email inserido deve ser válido',
          'passwordInput.required' => 'Insira sua senha para realizar login',
      ]);
  }

  public static function validateRegister(Request $request){
    $request->validate([
          'nameInput'                  => 'required|min:6|max:30',
          'emailInput'                 => 'required|email|unique:users,email',
          'tagInput'                   => 'required|max:10|regex:/^[a-zA-Z0-9]+$/|unique:users,tag',
          'passwordInput'              => 'required|min:5|max:24|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
          'passwordInput_confirmation' => 'required|same:passwordInput'
      ],[
          'nameInput.required' => 'O nome de usuário é obrigatório',
          'nameInput.min'      => 'O nome deve ter no mínimo 6 caracteres',
          'nameInput.max'      => 'O nome deve ter no máximo 30 caracteres',

          'emailInput.required' => 'O Email é obrigatório',
          'emailInput.email'    => 'O Email deve ser válido',
          'emailInput.unique'   => 'O Email já foi cadastrado',

          'tagInput.required' => 'A Tag de usuário é obrigatória',
          'tagInput.max'      => 'A Tag deve ter no máximo 10 caracteres',
          'tagInput.regex'    => 'A Tag não pode ter caracteres especiais',
          'tagInput.unique'   => 'A Tag já foi cadastrada',

          'passwordInput.required' => 'A Senha é obrigatório',
          'passwordInput.min'      => 'A Senha deve ter no mínimo 5 caracteres',
          'passwordInput.max'      => 'A Senha deve ter no máximo 24 caracteres',
          'passwordInput.regex'    => 'A Senha deve ter pelo menos 1 letra maiúscula, 1 letra minúscula e 1 digito',

          'passwordInput_confirmation.required' => 'A Senha precisa ser confirmada',
          'passwordInput_confirmation.same'     => 'As senhas inseridas não batem'
      ]);

  }

  public static function getUserInfos(UserModel $user){
    $user = [
      'id'              => EncryptionClass::encryptId($user->id),
      'email'           => $user->email,
      'username'        => $user->username,
      'tag'             => $user->tag,
      'profile_img' =>     ProfileClass::profileImage($user)
    ];
    return $user;
  }

  public static function existsUserWithThisLogin(Request $request){
    $email = $request->input('emailInput');
    $password = $request->input('passwordInput');

    if(UserModel::getLoginOrUser($email,$password)){
      return True;
    }
    return False;
  }

  public static function createNewUser(Request $request){
    $user = new UserModel();

    $user->email           = $request->input('emailInput');
    $user->password        = bcrypt($request->input('passwordInput'));
    $user->username        = $request->input('nameInput');
    $user->tag             = $request->input('tagInput');
    $user->followersNumber = 0;
    $user->followingNumber = 0;
    $user->last_login       = Carbon::now();
    $user->validation_token= Str::random(64);
    $user->save();

    session(['email_validation'=>$user->email]);

    return $user;
  }

  public static function sendValidationEmailTo(UserModel $user){
    $confirmation_link = route('validation',['token'=>$user->validation_token]);
    Mail::to($user->email)->send(new RegisterEmailConfirmation($user->username,$confirmation_link));
  }

  public static function validateUserEmail($token){
    $user = UserModel::where('deleted_at',null)->where('email_verified_at',null)->where('validation_token',$token)->first();
    if($user){
      $user->email_verified_at = Carbon::now();
      $user->save();
      return $user;
    }
    return False;
  }

}