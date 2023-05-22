<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validateMessage = [
            'required' => 'Request Cannot be empty',
            'email' => 'Format Email Only',
            'unique' => 'Email is Already use'
        ];
        
        $validation = Validator::make($request -> all(),[
            'name' => 'required',
            'email' => 'email|unique:users,email',
            'password' => 'required'
        ],$validateMessage);

        if($validation -> fails()){
            $validateErrMsg = $validation -> errors();
            return $this -> responseError($validateErrMsg);
        }

        $regis = User::create([
            'name' => $request -> name,
            'email' => $request -> email,
            'password' => Hash::make($request -> password),
        ]);
        return $this -> responseSuccess('Register is successfully');
    }

    public function login(Request $request){
        $validateMessage = [
            'required' => 'Request Cannot be empty',
            'email' => 'Format Email Only',
            'unique' => 'Email is Already use'
        ];
        
        $validation = Validator::make($request -> all(),[
            'email' => 'email',
            'password' => 'required'
        ],$validateMessage);

        if($validation -> fails()){
            $validateErrMsg = $validation -> errors();
            return $this -> responseError($validateErrMsg);
        }

        $getUser = User::where('email', $request -> email) -> first();
        
        if(is_null($getUser)){
            return $this -> responseError("yours email is not found");

        }else if(!Hash::check($request -> password, $getUser -> password)){
            return $this -> responseError("yours password is wrong");
            
        }
       $token = $getUser -> createToken($getUser -> email) -> plainTextToken;
       return $this -> responseSuccess("Authenticated is successfully", ['token' => $token, 'user' => $getUser]);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return $this -> responseSuccess("Logout Successfully");
    }
}