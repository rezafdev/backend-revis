<?php

namespace App\Helpers;

use Faker\Factory;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{

    /**
     * Generate the URL that will return a random image
     *
     * Set randomize to false to remove the random GET parameter at the end of the url.
     *
     * @example 'https://via.placeholder.com/640x480/?12345'
     *
     * @param integer $width
     * @param integer $height
     * @param bool $randomize
     *
     * @return string
     */
    public static function imageUrl(int $width = 640, int $height = 480,bool $randomize = true): string
    {
        $baseUrl = "https://via.placeholder.com/";
        $url = "{$width}x{$height}/";

        if ($randomize) {
            $url .= '?' . mt_rand();
        }

        return $baseUrl . $url;
    }

    /**  Generate the URL that will return a random image from picsum.photos
     * @return string|null
     */
    public static function imageUrlPicsum(): ?string
    {
        try {
            $imageId = Factory::create()->numberBetween(1, 1030);

            $res = (new Client())->get("https://picsum.photos/id/{$imageId}/info");
            $res = json_decode($res->getBody()->getContents(), true);
            return $res['download_url'] ?? $res['url'];
        } catch (\Throwable $e){}
        return null;
    }

    /** Generate the URL that will return a random avatar from pravatar.com
     * @param int $size
     *
     * @return string
     */
    public static function imageUrlAvatar(int $size = 300, $n = 0): string
    {
        $number = $n <= 0 ? Factory::create()->numberBetween(1,70) : $n;
        $size = max($size, 150);
        return "https://i.pravatar.cc/{$size}?img={$number}";
    }


    /** Generate the URL that will return a random image from placeimg.com
     * @param int $width
     * @param int $height
     * @param string $tag
     *
     * @return string
     */
    public static function imageUrlPlaceimg(int $width = 640, int $height = 480, string $tag = 'any'): string
    {
        return "https://placeimg.com/{$width}/{$height}/{$tag}";
    }

    /** Generate the URL that will return a random image from placeimg.com
     * @param int $width
     * @param int $height
     * @param string $tag
     *
     * @return string
     */
    public static function imageUrlLoremFlickr (int $width, int $height, string $tag = null) : string {
        $base = "https://loremflickr.com/{$width}/{$height}";
        $tag = empty($tag) ? "" : "/{$tag}";
        $random = rand(1, 20000);
        return "{$base}{$tag}?random={$random}";
    }

    /**
     * Download a remote random image to disk and return its location
     *
     * Requires curl, or allow_url_fopen to be on in php.ini.
     *
     * @param null $dir
     * @param int $width
     * @param int $height
     * @param bool $fullPath
     * @param bool $randomize
     * @return bool|\RuntimeException|string
     * @example '/path/to/dir/13b73edae8443990be1aa8f1a483bc27.jpg'
     */
    public static function image($dir = null, int $width = 640, int $height = 480, bool $fullPath = true, bool $randomize = true)
    {
        $dir = is_null($dir) ? sys_get_temp_dir() : $dir;
        if (!is_dir($dir) || !is_writable($dir)) {
            throw new \InvalidArgumentException(sprintf('Cannot write to directory "%s"', $dir));
        }
        $name = md5(uniqid(empty($_SERVER['SERVER_ADDR']) ? '' : $_SERVER['SERVER_ADDR'], true));
        $filename = $name .'.jpg';
        $filepath = $dir . DIRECTORY_SEPARATOR . $filename;

        $url = static::imageUrl($width, $height, $randomize);

        if (function_exists('curl_exec')) {
            $fp = fopen($filepath, 'w');
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            $success = curl_exec($ch) && curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200;
            fclose($fp);
            curl_close($ch);

            if (!$success) {
                unlink($filepath);

                return false;
            }
        } elseif (ini_get('allow_url_fopen')) {
            $success = copy($url, $filepath);
        } else {
            return new \RuntimeException('The image formatter downloads an image from a remote HTTP server. Therefore, it requires that PHP can request remote hosts, either via cURL or fopen()');
        }

        return $fullPath ? $filepath : $filename;
    }


    public static function picsumImage(int $width = 640, int $height = 480) {
        $n = Factory::create()->numberBetween(1, 100);
        return "https://picsum.photos/$width/$height?random=$n";
    }


}
