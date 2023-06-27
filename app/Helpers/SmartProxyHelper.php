<?php

namespace App\Helpers;


use Faker\Factory;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class SmartProxyHelper {

    const ROTATING_PROXY_URL = "http://user-spef688358:Master1363@at.smartproxy.com:35000";
    const HOST = "gate.smartproxy.com";

    const STICKY__URL_FORMAT = "http://user-spef688358-sessionduration-%d:Master1363@gate.smartproxy.com:%s";
    const STICKY_PORTS = [
        10000, 10001, 10002, 10003, 10004,
        10005, 10006, 10007, 10008, 10009,
        10010, 10011, 10012, 10013, 10014
    ];
    const STICKY_DURATION = 1;

    public static function getProxyUrl(bool $sticky = false) {
//        if($sticky) {
//            $port = self::STICKY_PORTS[array_rand(self::STICKY_PORTS)];
//            return sprintf(self::STICKY__URL_FORMAT, self::STICKY_DURATION, $port);
//        }
        return self::ROTATING_PROXY_URL;
    }


}