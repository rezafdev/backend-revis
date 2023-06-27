<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Base\JsonRequestEx;

class AdminPostDoctorRequest extends JsonRequestEx
{
    public function rules(): array
    {
        return [
            'type' => ['required', 'int'],
            'name' => ['required', 'string'],
            'bio' => ['string', 'nullable'],
            'skill_mental' => ['required', 'boolean'],
            'skill_beauty' => ['required', 'boolean'],
            'skill_blood' => ['required', 'boolean'],
            'avatarUrl' => ['nullable', 'string']
        ];
    }

}
