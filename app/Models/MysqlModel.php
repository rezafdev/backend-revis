<?php

namespace App\Models;

use App\Helpers\Utils;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MysqlModel extends Model {
    protected $connection = 'mysql';
    public $timestamps = true;
    protected $guarded = [];
    protected $hidden = ['pivot', 'token', 'password', 'created_at', 'updated_at'];
    protected $dateFormat = 'Y-m-d H:i:s';
    public static $snakeAttributes = false;

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format($this->getDateFormat());
    }

    public function getAuthUser() {
        return Auth::guard('api')->user() ?? null;
    }

    public function getAuthUserId() {
        return Auth::guard('api')->user()->id ?? null;
    }

    public function getCreatedAtJalaliAttribute(): string
    {
        return Utils::jalaliDateTimeOf($this->created_at ?? null);
    }

    public function getUpdatedAtJalaliAttribute(): string
    {
        return Utils::jalaliDateTimeOf($this->updated_at ?? null);
    }

    public function getReleasedAtJalaliAttribute(): string
    {
        return Utils::jalaliDateTimeOf($this->released_at ?? null);
    }

    public function assetUrl($url) {
        if(!empty($url)) {
            if(is_string($url)) {
                return Str::startsWith($url, 'http') ? $url : asset($url);
            }
            if(is_array($url)) {
                $result = [];
                foreach ($url as $item) {
                    if(!empty($item) && is_string($item)) {
                        array_push($result, Str::startsWith($item, 'http') ? $item : asset($item));
                    }
                }
                return $result;
            }
        }
        return null;
    }

}
