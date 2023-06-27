<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Traits\ApiPlatform;
use App\Http\Controllers\Traits\AuthGuard;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use AuthGuard;
    use ApiPlatform;

    const DEBUG_MODE = false;
    const MONGO_ID_REGEX_PATTERN = "/^(?=[a-f\d]{24}$)(\d+[a-f]|[a-f]+\d)/i";

    const JWT_AUTH_MIDDLEWARE = "jwt.auth";
    const SANCTUM_AUTH_MIDDLE = "auth:sanctum";


    public function __construct() {
//        $this->setupJwt();
    }


}
