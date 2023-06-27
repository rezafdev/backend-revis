<?php

namespace App\Helpers;


use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Exception;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Morilog\Jalali\Jalalian;
use StdClass;

class Utils {

    public static function mobileInterFormat($mobile, $countryCode = "98" , $plusSign = false) {
        if(empty($mobile)) return null;
        $starting = $plusSign ? "+$countryCode" : $countryCode;
        $len = strlen($mobile);
        if($len == 12) return $mobile;
        else if($len >= 10) {
            return $starting . substr($mobile, -10);
        }
        return $mobile;
    }

    public static function mobileNormalFormat($mobile, $leadingZero = true) {
        if(empty($mobile)) return null;
        if($leadingZero) {
            return '0' . substr($mobile, -10);
        } else {
            return substr($mobile, -10);
        }
    }

    public static function filePath($file) {
        if(!empty($file) && !str_starts_with($file, 'http') && !str_starts_with($file, 'storage')){
            return "storage/uploads/{$file}";
        }
        return false;
    }

    public static function isMobileNumberValid($mobile, bool $isCountryCodeRequired = true) : bool {
        if(empty($mobile)) return false;
        $LEN = $isCountryCodeRequired ? 12 : 11;
        if(strlen($mobile) < $LEN) return false;
        if(!$isCountryCodeRequired) {
            return (str_starts_with($mobile, '09'));
        }
        return true;
    }

    public static function phoneNumbersEqual($num1, $num2): bool
    {
        if(strlen($num1) < 2 || strlen($num2) < 2) return ($num1 == $num2);
        if(str_starts_with($num1, '98')){
            $num1 = substr($num1, 2);
        }
        if(str_starts_with($num2, '98')){
            $num2 = substr($num2, 2);
        }
        return $num1 == $num2;
    }

    public static function formatShortCode($shortCode, $prefix = '+98') {
        if(empty($shortCode)) return $shortCode;
        else if(str_starts_with($shortCode, '98')) {
            return $prefix . substr($shortCode, 2);
        } else
            return $prefix . $shortCode;
    }

    public static function getTableColumns($table_name, $con = 'mysql'): array
    {
        $columns = DB::connection($con)->select('show columns from ' . $table_name);

        $res = [];
        foreach ($columns as $value) {
            $res[] = $value->Field;
            //echo "'" . $value->Field . "' => '" . $value->Type . "|" . ( $value->Null == "NO" ? 'required' : '' ) ."', <br/>" ;
        }
        return $res;
    }

    public static function persianToEnglishNumbers($string) {
        if(!$string || strlen($string) == 0){
            return $string;
        }
        return strtr($string, array('/' => '/','۰'=>'0', '۱'=>'1', '۲'=>'2', '۳'=>'3', '۴'=>'4', '۵'=>'5', '۶'=>'6', '۷'=>'7', '۸'=>'8', '۹'=>'9', '٠'=>'0', '١'=>'1', '٢'=>'2', '٣'=>'3', '٤'=>'4', '٥'=>'5', '٦'=>'6', '٧'=>'7', '٨'=>'8', '٩'=>'9'));
    }

    public static function startsWithNumber($str): bool
    {
        return preg_match('/^\d/', $str) === 1;
    }

    public static function hasAlphabetCharacter($str): bool
    {
        return preg_match("/[a-z]/i", $str) === 1;
    }

    public static function isAppVersionNumber($str): bool
    {
        try {
            if(str_starts_with($str, 'app_')) return true;
            return self::startsWithNumber($str) && ! self::hasAlphabetCharacter($str);
        } catch (Exception $e){
            return false;
        }
    }


    public static function formatTextMessage($message, $params = null) {
        if(empty($message)) return "";
        if(empty($params)){
            return $message;
        }
        $str = $message;
        $cnt = count($params);
        for($i = 0;$i < $cnt; $i++) {
            $str = str_replace("#{$i}", $params[$i], $str);
        }
        return $str;
    }

    public static function splitDateTime($dateTime, $format = 'Y-m-d H:i:s'): array
    {
        try {
            if(empty($dateTime)) {
                $carbon = Carbon::now('Asia/Tehran');
            } else {
                $carbon = Carbon::createFromFormat($format, $dateTime);
            }
        } catch (Exception $e) {
            $carbon = Carbon::now('Asia/Tehran');
        }

        return ['dateTime' => $carbon->toDateTimeString(), 'date' => $carbon->toDateString(), 'time' => $carbon->toTimeString()];
    }

    public static function bindTextParams($text, array $params){
        if(empty($params) || empty($text)) return $text;
        $cnt = count($params);
        $str = $text;
        for($i = 0;$i < $cnt; $i++) {
            $str = str_replace("#{$i}", $params[$i], $str);
        }
        return $str;
    }

    public static function customNumberFormat($n, $precision = 3): string
    {
        if ($n < 1000000) {
            $n_format = number_format($n);
        } else if ($n < 1000000000) {
            $n_format = number_format($n / 1000000, $precision) . 'M';
        } else {
            $n_format = number_format($n / 1000000000, $precision) . 'B';
        }
        return $n_format;
    }

    public static function arrayCastRecursive($array)
    {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $array[$key] = self::arrayCastRecursive($value);
                }
                if ($value instanceof StdClass) {
                    $array[$key] = self::arrayCastRecursive((array)$value);
                }
            }
        } else if ($array instanceof StdClass) {
            return self::arrayCastRecursive((array)$array);
        }
        return $array;
    }

    /** @noinspection PhpUndefinedFunctionInspection */
    public static function GUID(): string
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535),
            mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }


    public static function hashtagsFromString($string) {
        try {
            preg_match_all('/#([^\s]+)/', $string, $matches);
            $hash = $matches[0];
            if(!empty($hash))
                return $hash;
        } catch (Exception $e){}
        return null;
    }

    private static function publicPathFromUrl($url) {
        if(empty($url)) return null;
        if(Str::startsWith($url, "storage/")) {
            return public_path($url);
        }
        return $url;
    }

    /** @noinspection PhpComposerExtensionStubsInspection */
    public static function mimeType($url): ?string
    {
        if(empty($url)) return null;
        $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
        if(!$ext) {
            try {
                $fpath = self::publicPathFromUrl($url);
                if(empty($fpath)) throw new Exception("wrong url");
                $mimetype = null;
                if(function_exists('finfo_file') && function_exists('finfo_open')) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimetype = finfo_file($finfo, $fpath);
                } else if(function_exists('mime_content_type')) {
                    $mimetype = mime_content_type($fpath);
                }

                if(!empty($mimetype)) {
                    if(Str::contains($mimetype, "image")) {
                        return 'image';
                    } else if(Str::contains($mimetype, "audio")) {
                        return 'audio';
                    } else if(Str::contains($mimetype, 'video')) {
                        return 'video';
                    } else {
                        return 'doc';
                    }
                }
            } catch (Exception $e){
                return 'doc';
            }
        }

        switch ($ext) {
            case 'ogg':
            case 'wav':
            case "mp3":
                return 'audio';
            case 'avi':
            case 'mp4':
            case "mov":
                return 'video';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'bmp':
            case 'tiff':
            case 'jpe':
            case 'gif':
            case 'ico':
            case 'tif':
            case 'svg':
                return 'image';
            default:
                return 'doc';
        }
    }

    public static function mimeTypeCode(string $mimeType): int
    {
        if(empty($mimeType) || !is_string($mimeType))  return -1;
        if(Str::startsWith($mimeType, 'image'))
            return 1;
        else if(Str::startsWith($mimeType, 'audio'))
            return 2;
        else if(Str::startsWith($mimeType, 'video'))
            return 3;
        else
            return 4;
    }

    public static function DbGenerateUniqueValue(string $table, string $fieldName, int $length = 6, string $chars = null): ?string
    {
        if(!$fieldName) return null;
        if(!$chars) {
            $chars = '[A-Z1-9]';
        }
        $PATTERN = sprintf("%s{%d}", $chars, $length);
        $faker = Factory::create();
        do {
            $val = $faker->regexify($PATTERN);
        } while(DB::connection('mysql')->table($table)->where($fieldName, $val)->exists());
        return $val;
    }

    public static function jalaliDateTimeOf($datetime, $outputFormat = "d %B Y", $format = "Y-m-d H:i:s"): string
    {
        try {
            $date = Jalalian::fromCarbon(Carbon::createFromFormat($format, $datetime));
            if(empty($outputFormat)) {
                return $date->toString();
            } else {
                return $date->format($outputFormat);
            }
        } catch (Exception $e){}
        return "";
    }

    public static function strip_zeros($number, $as_float = true) {
        $s =  preg_replace("/\.?0+$/", "", $number);
        if ($as_float)
            return (float) $s;
        else
            return $s;
    }

    public static function removeHttpFromUrl($url) {
        $disallowed = ['http://', 'https://'];
        foreach($disallowed as $d) {
            if(str_starts_with($url, $d)) {
                return str_replace($d, '', $url);
            }
        }
        return $url;
    }

}
