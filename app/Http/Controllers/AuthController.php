<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $dataRegis = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ];
        $rulesDataRegis = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|max:24',
        ];
        $isValidDataIns = Validator::make($dataRegis, $rulesDataRegis);
        if (!$isValidDataIns->passes()) {
            $apiRes['meta'] = [
                'code' => '400',
                'type' => 'fail',
                'message' => $isValidDataIns->messages()->first(),
            ];

            return new Response($apiRes, 400);
        }

        $user = User::create([
            'name' => $dataRegis['name'],
            'email' => $dataRegis['email'],
            'password' => Hash::make($dataRegis['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }
}
