<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Base\JsonRequestEx;

class AdminPostTherapyRequest extends JsonRequestEx
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string'],
            'minDuration' => ['required', 'int'],
            'maxDuration' => ['required', 'int'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['nullable', 'string'],
            'doctorId' => ['required', 'int', 'exists:App\Models\Doctor,id'],
        ];
    }

}
