<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        // 验证请求数据
        $request->validate([
            'userLogin' => 'required',
            'password' => 'required|string',
        ]);

        // 查找用户并验证密码
        if (!$request->userLogin) {
            throw ValidationException::withMessages([
                'userLogin' => ['The email or phone is required.'],
            ]);
        }
        if ($request->userLogin) {
            $user = User::where('email', $request->userLogin)
            ->orWhere('phone', $request->userLogin)
            ->first();
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'userLogin' => ['The provided credentials are incorrect.'],
            ]);
        }

        // 检查用户role
        if (!$user->role) {
            throw ValidationException::withMessages([
                'role' => ['The user does not have a valid role.'],
            ]);
        }

        // 生成 API token
        $token = $user->createToken('MassageBookingSystem')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }
}
