<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'logout']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('name', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithSessionDetails($token);
        }

        return $this->respondUnauthorized();
    }

    public function me()
    {
        return $this->respondWithSessionDetails($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if($this->guard()->check()){
            $this->guard()->logout();
        }

        return $this->respondOk(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithSessionDetails($this->guard()->refresh());
    }

    protected function respondWithSessionDetails($token)
    {
        return $this->respondOk([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
            'user' => $this->guard()->user()
        ]);
    }

    public function guard()
    {
        return Auth::guard();
    }
}
