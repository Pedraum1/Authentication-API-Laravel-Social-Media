<?php

namespace App\Http\Controllers;

use App\Classes\ApiClass;
use App\Classes\AuthClass;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
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

    public function register(RegisterRequest $request){
        $request->validated();

        $user = AuthClass::createNewUser($request);
        AuthClass::sendValidationEmailTo($user);

        return ApiClass::success($user->email);
    }

    public function validatingEmail($token){
        $user = AuthClass::validateUserEmail($token);
        if($user){
            return ApiClass::success($user);
        }
        return ApiClass::error('Not cabapable to Validate Email');
        //TODO: ADD ERROR ON VALIDATING USER EMAIL RESPONSE
    }
}
