<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // return $request->input('username');
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'username' => 'required|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $user = User::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password'))
         ]);

        $user->assignRole('user');

        return response()
            ->json(['message' => 'Berhasil registrasi' ], 200);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('username', 'password')))
        {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::with('roles')->where('username', $request['username'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['message' => 'Login berhasil','access_token' => $token, 'user' => $user, 'token_type' => 'Bearer', ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'status' => 200,
            'message' => 'Berhasil Logout'
        ];
    }
}
