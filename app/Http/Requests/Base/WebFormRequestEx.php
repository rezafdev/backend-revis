<?php

namespace App\Http\Requests\Base;

use App\Helpers\Utils;
use App\Http\Exceptions\HttpExceptionEx;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class WebFormRequestEx extends FormRequestEx {


    protected function failedValidation(Validator $validator) {
        $errors = $validator->errors();
        $str = config("locale.fa.err_form_wrong_paremets", "Wrong Parameters");
        //        try {
        //            $str = $errors->first();
        //        } catch (\Exception $e){}
        return back()->with('message', $str)->with('alert-class', 'alert-danger');
    }

    public function messages() {
        return [
            'required' => 'Field :attribute is required',
        ];
    }

}
