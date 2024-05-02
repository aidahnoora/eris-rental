<?php

namespace App\Http\Controllers\WebAPI;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:6'],
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user = User::where('email', $request->email)->first();

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            $response = [
                'success' => true,
                'user' => $user,
                'token' => $tokenResult
            ];

            return response($response, 201);
        } catch (\Exception $error) {
            return response([
                'message' => 'Something went wrong',
                'error' => $error,
            ]);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

                if (!$user || !Hash::check($request->password, $user->password)) {
                    return response([
                        'success'   => false,
                        'message' => ['These credentials do not match our records.']
                    ], 404);
                }

                $token = $user->createToken('ApiToken')->plainTextToken;

                $response = [
                    'success'   => true,
                    'user'      => $user,
                    'token'     => $token
                ];

            return response($response, 201);
        } catch (\Exception $error) {
            return response([
                'message' => 'Something went wrong',
                'error' => $error->getMessage(),
            ]);
        }
    }

    public function logout(Request $request)
    {
        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'Token revoken!'
        ], 200);
    }
}
