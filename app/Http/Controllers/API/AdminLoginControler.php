<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLogin;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AdminLoginControler extends Controller
{

    public function register(Request $request){
        $admin=Admin::create([
            'username' => $request['username'],
            'password' => bcrypt($request['password']),
        ]);
        return response()->json([
            'message'=> 'Admin created successfully',
            'data'=>$admin
        ]);
    }
    public function login(AdminLogin $request){
        $param = $request->only(['username', 'password']);
        $token = null;
        try {
            if (!$token = JWTAuth::attempt($param)) {
                return response()->json('invalid_username_or_password');
            }
        } catch (JWTException $e) {
            return response()->json('failed_to_create_token');
        }
        return $this->respondWithToken($token);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    public function logout(Request $request)
    {
        auth()->logout(true);
        toast('Logout success','success','top-right');
        return response()->json(['message'=>'Logout success']);
    }
}
