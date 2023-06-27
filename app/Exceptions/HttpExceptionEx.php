<?php

namespace App\Exceptions;


use Symfony\Component\HttpKernel\Exception\HttpException;

class HttpExceptionEx extends HttpException {

    const STATUSCODE_BAD_REQUEST = 400;
    const STATUSCODE_UNAUTHORIZED = 401;
    const STATUSCODE_PAYMENT_REQUIRED = 402;
    const STATUSCODE_ACCESS_DENIED = 403;
    const STATUSCODE_NOT_FOUND = 404;
    const STATUSCODE_NOT_ACCEPTABLE = 406;
    const STATUSCODE_UNPROCESSABLE = 422;

    public $errors;

    /**
     * @param string|null $message The internal exception message
     * @param null $errors
     * @param int $statusCode The internal exception code
     * @param array $headers
     */
    public function __construct(string $message = null, $errors = null, $statusCode = 400, array $headers = []) {
        $this->errors = $errors;
        parent::__construct(self::STATUSCODE_UNPROCESSABLE, $message, null, $headers, 0);
    }

}
