<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register()
    {
        $validator=Validator::make(request()->all(),[
            'name' => 'required|max:255|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:8',
        ]);
        if($validator->fails()){
            return ResponseFormatter::error($validator, $validator->messages(), 400);
        }
        $user=User::create([
            'name' => request()->name,
            'email' => request()->email,
            'password' => Hash::make(request()->password),
        ]);
        $user->assignRole(request()->role);

        return ResponseFormatter::success($user->load('roles'),'Data user berhasil diregistrasi');
    }
}
