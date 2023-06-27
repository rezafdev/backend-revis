<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\AdminController;
use App\Http\Controllers\Base\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Models\AdminUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends AdminController {


    public function __construct()
    {
        parent::__construct();
        $this->middleware(self::SANCTUM_AUTH_MIDDLE)->except([
            'login',
            'forgetPassword',
        ]);
    }


    public function login(AdminLoginRequest $request): JsonResponse {
        $credentials = $request->validated();
        $user = AdminUser::where('email', $credentials['email'])->first();
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('admin')->plainTextToken;
        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user
        ]);
    }

    public function me(): JsonResponse
    {
        $user = $this->authUser();
        return response()->json($user);
    }



}
