<?php

namespace App\Http\Requests\Base;

use App\Helpers\Utils;
use App\Http\Exceptions\HttpExceptionEx;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class JsonRequestEx extends FormRequestEx {

    public function validationData() : array {
        $data = json_decode($this->getContent(), true);
        if(!empty($data)) {
            foreach ($data as $key => $value) {
                if($key && is_string($value)) {
                    $data[$key] = Utils::persianToEnglishNumbers($data[$key]);
                }
            }
        }
        return $data;
    }

}
