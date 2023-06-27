<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;


class Handler extends ExceptionHandler
{
    const DEBUG_MODE = true;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function report(Throwable $e)
    {
        parent::report($e);
    }

    private function isApiUri(Request $request) : bool{
        return str_starts_with($request->getRequestUri(), '/api/');
    }

    public function render($request, Throwable $e) {
        if($this->isApiUri($request)) {
            return $this->handleApiException($request, $e);
        }
        return parent::render($request, $e);
    }


    private function handleApiException(Request $request, Throwable $exception): JsonResponse
    {
        $exception = $this->prepareException($exception);
        $debug_trace = $request->hasHeader('x-debug-trace') || env('APP_DEBUG', false);

        if($exception instanceof AuthenticationException) {
            $statusCode = 401;
        } else if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = intval($exception->getCode() ?: 500);
        }

//        if($exception instanceof TokenBlacklistedException) {
//            $statusCode = 403;
//        }

        $response = [
            'success' => false,
            'statusCode' => $statusCode,
            'statusMessage' => "Error",
            'errorMessage' => $exception->getMessage(),
            'errors' => ($exception instanceof HttpExceptionEx) ? $exception->errors : null,
        ];

        switch ($statusCode) {
            case 400:
                $response['statusMessage'] = "Bad Request";
                break;
            case 401:
                $response['statusMessage'] = 'Unauthorized';
                break;
            case 403:
                $response['statusMessage'] = 'Forbidden';
                break;
            case 404:
                $response['statusMessage'] = 'Not Found';
                break;
            case 405:
                $response['statusMessage'] = 'Method Not Allowed';
                break;
            case 406:
                $response['statusMessage'] = 'Not Acceptable';
                break;
            case 422:
                $response['statusMessage'] = 'Unprocessable Entity';
                break;
            default:
                $response['statusMessage'] = ($statusCode == 500) ? 'Server Error' : "ERROR";
                break;
        }

        if (self::DEBUG_MODE) {
            $response['code'] = $exception->getCode();
            if($debug_trace) $response['trace'] = $exception->getTrace();
        }

        if(empty($response['errorMessage'])) {
            $response['errorMessage'] = $response['statusMessage'] ?? "";
        }


        $http_status_code = $statusCode;
        if($http_status_code < 100 || $http_status_code >= 600) {
            $http_status_code = 400;
        }

        return response()->json($response, $http_status_code);
    }

}
