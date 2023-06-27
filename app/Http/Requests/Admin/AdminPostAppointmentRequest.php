<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Base\JsonRequestEx;

class AdminPostAppointmentRequest extends JsonRequestEx
{
    public function rules(): array
    {
        return [
            'patientId' => ['required', 'int', 'exists:App\Models\Patient,id'],
            'therapyId' => ['required', 'int', 'exists:App\Models\Therapy,id'],
            'beginDate' => ['required', 'date'],
            'beginTime' => ['nullable', 'string'],
        ];
    }


    public function validationData(): array
    {
        $data =  parent::validationData();
        $t = explode(":", $data['beginTime'] ?? "08:00" );
        $data['beginTime'] = sprintf("%s:%s:00", $t[0] ?? "08", $t[1] ?? "00");
        return $data;
    }
}
