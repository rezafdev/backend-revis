<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Traits\ApiPlatform;
use App\Http\Controllers\Traits\AuthGuard;
use App\Models\AdminUser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class AdminController extends Controller {

    const AUTH_USER_MODEL = AdminUser::class;
    const GUARD = "api_admin";


    public function __construct() {
        parent::__construct();
    }


}
