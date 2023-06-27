<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Base\JsonRequestEx;

class AdminPostPatientRequest extends JsonRequestEx
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'surname' => ['required', 'string'],
            'sexType' => ['required', 'int'],
            'birthday' => ['nullable', 'string'],
            'email' => ['nullable', 'string'],
            'phoneCountryCode' => ['nullable', 'string'],
            'phoneNumber' => ['nullable', 'string'],
            'status' => ['nullable', 'int']
        ];
    }

}
