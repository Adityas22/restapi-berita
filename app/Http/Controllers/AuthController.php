<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller

{
    //
    public function login(Request $request){
        //
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            // 'device_name' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['The provided credentials are incorrect.']
            ], 404);
        }

        $token = $user->createToken('user login')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response([
            'message' => 'Logout Success'
        ]);
    }

    public function me(Request $request){
        return $request->user();
    }
}