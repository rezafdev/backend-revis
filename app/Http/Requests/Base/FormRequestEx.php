<?php

namespace App\Http\Requests\Base;

use App\Exceptions\HttpExceptionEx;
use App\Helpers\LocaleHelper;
use App\Helpers\Utils;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FormRequestEx extends FormRequest {

    protected $casts = [];

    protected $default_values = [];

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator) {
        $errors = $validator->errors();
        $str = LocaleHelper::get("form_wrong_parameters", "en", "Wrong Parameters");
        try {
            $str = $errors->first();
        } catch (\Exception $e){}
        throw new HttpExceptionEx($str, $errors, HttpExceptionEx::STATUSCODE_UNPROCESSABLE);
    }

    public function validationData(): array {
        $data = parent::validationData();
        if(!empty($data)) {
            foreach ($data as $key => $value) {
                if($key && is_string($value)) {
                    $data[$key] = Utils::persianToEnglishNumbers($value);
                }
                $data[$key] = $this->castValue($key, $data[$key]);
            }
        }
        if(!empty($this->default_values)) {
            foreach ($this->default_values as $key => $value) {
                if(!isset($data[$key])) {
                    $data[$key] = $value;
                }
            }
        }
        return $data;
    }

    private function castValue($key, $value) {
        if(!is_array($this->casts)) return $value;

        try {
            if($this->casts && isset($this->casts[$key])) {
                $c = $this->casts[$key];

                return match ($c) {
                    'bool', 'boolean' => ($value === 'on' || $value),
                    'int', 'integer' => ((int)$value),
                    'string', 'str' => ((string)$value),
                    'float' => ((float)$value),
                    default => $value,
                };
            }
        } catch (\Exception $e1){}

        return $value;
    }

    public function authUser() : ?Authenticatable {
        return Auth::guard('api')->user() ?? null;
    }


}
