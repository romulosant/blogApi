<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request){


        $credentials = $request->only('email', 'password');


        if(!$token = auth('api')->attempt($credentials)){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'bearer'
        ]);

    }
    public function logout(Request $request){

        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function register(Request $request){

        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        $token = auth()->login($user);


        return response()->json([
            'message' => 'Successfully created user!',
            'user' => $user,
            'token' => $token
        ]);

    }

    public function me(Request $request){
        return response()->json($request->user());
    }
}
