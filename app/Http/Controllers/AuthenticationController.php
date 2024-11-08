<?php

namespace App\Http\Controllers;

use App\Classes\AuthClass;
use App\Models\UserModel;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    public function login(Request $request){
        AuthClass::validateLogin($request);
        if(AuthClass::existsUserWithThisLogin($request)){
            $email = $request->input('emailInput');
            $password = $request->input('passwordInput');
            $user = UserModel::getLoginOrUser($email,$password,True);
            if(!$user){
                return redirect()->back()->withInput()->with(['validation_errors'=>True]);
            }
            session(['user'=>AuthClass::getUserInfos($user)]);
            return redirect()->route('home');
        }
        return redirect()->back()->withInput()->with(['validation_errors'=>True]);
    }

    public function register(Request $request){
        AuthClass::validateRegister($request);
        $user = AuthClass::createNewUser($request);
        AuthClass::sendValidationEmailTo($user);

        return redirect()->route('validation_sended')->with(['email_validation'=>$user->email]);
    }

    public function logout(){
        session()->forget('user');
        return redirect()->route('index');
    }

    public function validatingEmail($token){
        $user = AuthClass::validateUserEmail($token);
        if($user){
            session(['user'=>AuthClass::getUserInfos($user)]);
            return redirect()->route('home');
        }
        return abort(404);
        //TODO: ADD ERROR ON VALIDATING USER EMAIL RESPONSE
    }

    public function emailValidationPage(){
        $email = session('email_validation');
        return view('auth.emailValidation',['email'=>$email]);
    }
}
