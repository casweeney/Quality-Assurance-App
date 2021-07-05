<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AccessCode;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signupStore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name'  => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 406);
        }

        if($request->role === 'developer'){
            $access = AccessCode::where(['role' => 'developer', 'code' => $request->accessCode])->first();
            if($access){
                $validity = "valid";
            } else {
                $validity = "invalid";
            }
        }

        if($request->role === 'qa_person') {
            $access = AccessCode::where(['role' => 'qa_person', 'code' => $request->accessCode])->first();
            if($access){
                $validity = "valid";
            } else {
                $validity = "invalid";
            }
        }

        if($validity === "valid"){
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->role = $request->role;
            $user->save();

            return response($user, 201);

        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid access code'
            ], 406);
        }

    }

    public function login(Request $request){
        $validator = \Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 406);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $user->isLoggedIn = true;
            $user->save();

            return response()->json([ 
                'user' => $user,
                'status' => 'success',
                'message' => 'Login successful'
            ], 200);

        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed: Incorrect details'
            ], 406);
        }
    }
}
