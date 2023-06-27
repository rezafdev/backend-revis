<?php /** @noinspection PhpUndefinedClassConstantInspection */


namespace App\Http\Controllers\Traits;


use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait ApiPlatform {

    public function platform() {
        return request()->header('platform', "");
    }

    public function platformIsAndroid() : bool {
        return $this->platform() === 'android';
    }

    public function platformIsIos() : bool {
        return $this->platform() === 'ios';
    }

    public function platformIsWeb() : bool {
        return $this->platform() === 'web';
    }



}
