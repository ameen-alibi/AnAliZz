<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $json_response = [];

    public function register(Request $request)
    {
        $validations = [
            'name' => 'required|string|max:100',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ];
        $validator = Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            $this->json_response = $validator->errors();
            //The function response is equivalent to send in C 
            return response()->json(['errors' => $this->json_response], 422);
            //A 422 status code indicates that the server was unable to process the request because it contains invalid data.
        }

        $request_data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ];
        $user = User::create($request_data);

        $this->json_response = [
            'message' => 'User registered successfully',
            'user' => $user
        ];

        //The request was successfully fulfilled and resulted in one or possibly multiple new resources being created.
        return response()->json($this->json_response, 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['token' => $token], 200);
        }

        return response()->json(['message' => 'Login Failed !'], 401);
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($user) {
                $request->user()->currentAccessToken()->delete();
                return response()->json(['message' => 'Logged out successfully'], 200);
            }

            return response()->json(['message' => 'Unauthenticated'], 401);
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
}