<?php

namespace App\Http\Controllers\MobileAPI;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:6'],
                'nama' => 'required',
                'identitas' => 'required',
                'jaminan' => 'required',
                'umur' => 'required',
                'no_hp' => 'required',
                'alamat' => 'required',
            ]);

            $newUser = User::create([
                'email' => $request->input('email'),
                // entah mengapa bcrypt dan hash di projek ini malah error (laravel postgre)
                'password' => $request->input('password'),
            ]);

            $tokenResult = $newUser->createToken('authToken')->plainTextToken;

            $newCustomer = Customer::create([
                'user_id' => $newUser->id,
                'nama' => $request->input('nama'),
                'identitas' => $request->input('identitas'),
                'jaminan' => $request->input('jaminan'),
                'umur' => $request->input('umur'),
                'no_hp' => $request->input('no_hp'),
                'alamat' => $request->input('alamat'),
            ]);

            $customer = $newUser->customer; // Mengambil data customer dari user

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $newUser,
                // 'customer' => $customer,
            ], 'User registered');
        } catch (ValidationException $e) {
            return ResponseFormatter::error([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 'Validation Failed', 422);
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error->getMessage(),
            ], 'Authentication Failed', 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required',
            ]);

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized',
                ], 'Authentication Failed', 500);
            }

            $user = User::where('email', $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                throw new Exception('Invalid Credentials');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'Authenticated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error->getMessage(),
            ], 'Authentication Failed', 500);
        }
    }

    public function fetch(Request $request)
    {
        $user = $request->user(); // Mendapatkan user yang sedang login
        $customer = $user->customer; // Mengambil data customer dari user

        // Mengembalikan response
        return ResponseFormatter::success([
            'user' => $user,
        ], 'Data profil user beserta customer berhasil diambil');
    }

    public function updateProfile(Request $request)
    {
        $data = $request->all();

        $user = Auth::user();

        $user->update($data);

        return ResponseFormatter::success([
            $user,
            'Profile Updated',
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success([
            null,
            'Token revoke',
        ]);
    }
}
