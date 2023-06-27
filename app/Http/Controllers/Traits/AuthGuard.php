<?php /** @noinspection PhpUndefinedClassConstantInspection */


namespace App\Http\Controllers\Traits;


use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

trait AuthGuard {

    public function auth(): Guard|StatefulGuard
    {
        return Auth::guard($this->getAuthGuardName());
    }

    public function getAuthGuardName() : ?string {
        return defined('static::GUARD') ? strval(static::GUARD) : 'api';
    }

    public function getAuthUserProviderClass() : ?string {
        return defined('static::AUTH_USER_MODEL') ? strval(static::AUTH_USER_MODEL) : User::class;
    }

    protected function authUser() : ?Authenticatable
    {
        return $this->auth()->user();
    }

    public function authUserId($defaultValue = 0) {
        try {
            return $this->authUser()->getAuthIdentifier();
        } catch (\Throwable $e){
            return $defaultValue;
        }
    }

    public function setupJwt() {
        Config::set('auth.providers.users.model', $this->getAuthUserProviderClass());
    }

    /** @noinspection PhpUndefinedClassInspection */
    public function invalidateJwtToken(string $tokenString) {
        try {
            if(!empty($tokenString)) {
                JWTAuth::manager()->invalidate(new Token($tokenString), false);
            }
        } catch (\Exception $e) {}
    }

}
