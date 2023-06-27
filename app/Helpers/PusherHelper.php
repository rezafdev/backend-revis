<?php
/**
 * Created by PhpStorm.
 * User: mma
 * Date: 12/2/20
 * Time: 13:50
 */

namespace App\Helpers;


use App\Models\ChatGroup;
use Illuminate\Support\Str;
use Pusher\Pusher;
use Pusher\PusherException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PusherHelper {
    const EVENT_BIKER_REQUESTED = "req.biker";

    const CHANNEL_BIKER = "biker.UID";

    /**
     *
     * @return Pusher
     */
    public static function getInstance() : ?Pusher {
        try {
            $app_key = config('broadcasting.connections.pusher.key');
            $app_secret = config('broadcasting.connections.pusher.secret');
            $app_id = config('broadcasting.connections.pusher.app_id');
            $app_options = config('broadcasting.connections.pusher.options', []);

            $pusher = new Pusher(
                $app_key,
                $app_secret,
                $app_id,
                $app_options
            );
            return $pusher;
        } catch (PusherException $e){}
        return null;
    }

    /**
     * @param $socket_id
     * @param $channel_name
     * @param null $user_id
     * @param null $user_info
     * @return string
     * @throws PusherException
     */
    public static function auth($socket_id, $channel_name, $user_id = null, $user_info = null) : string {
        $is_presence = Str::start($channel_name, 'presence-');
        $ch = Str::replaceFirst('presence-', "",
            Str::replaceFirst("private-", "",
                $channel_name
            ));

        if( !self::validateChannelAuth($ch, $user_id) ) {
            throw new AccessDeniedHttpException();
        }

        if ($is_presence) {
            return self::getInstance()->presence_auth($channel_name, $socket_id, $user_id, $user_info);
        } else {
            return self::getInstance()->socket_auth($channel_name, $socket_id);
        }

    }


    private static function validateChannelAuth(string $channel, $user_id) : bool {
        $id = intval( Str::afterLast($channel, '.') );
        return $user_id === $id;
    }

}
