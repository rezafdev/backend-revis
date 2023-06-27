<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Base\JsonRequestEx;

class AdminLoginRequest extends JsonRequestEx
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }
}
