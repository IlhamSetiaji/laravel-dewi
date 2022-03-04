<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login()
    {
        $validator=Validator::make(request()->all(),[
            'email' => 'required|email',
            'password' => 'min:8|required',
        ]);
        if($validator->fails()){
            return ResponseFormatter::error($validator, $validator->messages(), 400);
        }

        $user = User::where('email', request()->email)->first();
        if($user)
        {
            if(Hash::check(request()->password, $user->password))
            {
                Auth::login($user);
                $token=$user->createToken('auth_token')->plainTextToken;
                $data=[
                    'Token Type' => 'Bearer Token',
                    'Token' => $token,
                    'user' => $user->load('roles'),
                ];
                return ResponseFormatter::success($data,'Login berhasil');
            }
            return ResponseFormatter::error(null,'Password user salah', 400);
        }
        return ResponseFormatter::error(null,'Email user tidak ditemukan', 404);
    }

    public function logout()
    {
        $token = request()->user()->currentAccessToken()->delete();

        return ResponseFormatter::success(
            $token,
            'Token Revoked'
        );
    }
}
