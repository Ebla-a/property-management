<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{

public function register(RegisterUserRequest $request)
{
    $user=User::create([
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>Hash::make($request->password)
    ]);
     $user->assignRole('customer');
        $token = $user->createToken(
            'auth_token',
            $user->getRoleNames()->toArray()
        )->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'token'   => $token,
            'user'    => new UserResource($user),
        ], 201);
    }

public function login(Request $request)
{
    $request->validate([
        'email'=>'required|string|email',
        'password'=>'required|string'
    ]);
    if(!Auth::attempt($request->only('email','password')))
        return response()->json([
    'messsage'=>'invaled email or password'
        ],401);

        $user=User::where('email',$request->email)->firstOrFail();
        $token=$user->createToken('auth_token',$user->getRoleNames()->toArray())->plainTextToken;
        return response()->json([
        'message'=>'Login Successfully',
        'Token'=>$token,
        'user'    => new UserResource($user),
    ],201);
}

public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();
    return response()->json([
        'message'=>'Logout Successfully'
    ]);
}
}
