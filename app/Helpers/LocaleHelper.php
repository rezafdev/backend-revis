<?php


namespace App\Helpers;


class LocaleHelper {

    const DEFAULT_LOCALE = "en";


    public static function get(string $key, string $locale = self::DEFAULT_LOCALE, string $defaultValue = null) {
        if($defaultValue === null) $defaultValue = $key;
        return config("locale.$locale.$key", $defaultValue);
    }


}
