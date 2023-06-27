<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;
use Rct567\DomQuery\DomQuery;

class GoogleFontCheckHelper {

    private bool $isSilent = false;
    private ?string $website;

    private const MATCHES = [
        "https://fonts.googleapis.com",
        "https://fonts.gstatic.com",
    ];
    private const FONT_EXTENSIONS = [
        "ttf", "woff", "otf", "woff2", "eot"
    ];

    public function __construct(?string $website, bool $isSilent = false) {
        $this->website = $website;
        $this->isSilent = $isSilent;
    }

    public static function newInstance(string $website, bool $isSilent = false): GoogleFontCheckHelper {
        return new GoogleFontCheckHelper($website, $isSilent);
    }

    private function httpClient(): Client {
        return new Client([
            'debug' => false,
            'connect_timeout' => 4,
            'timeout' => 10
        ]);
    }

    public function consoleLog($text, $tag = null): void {
        if( !$this->isSilent ) {
            echo $text . PHP_EOL;
        }
    }


    private function allLinksFromString(string $string): array {
        if(empty($string)) return [];
        $matches = [];
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $string, $matches);
        return $matches[0] ?? [];
    }

    public function start(): array|false {
        try {
            $res = $this->httpClient()->get($this->website)->getBody()->getContents();
            $dom = new DomQuery($res);
            $links = $dom->find("head")->children('link');

            $results = [];
            foreach ($links as $link) {
                $href = $link->attr('href') ?? "";
                $rel = strtolower( $link->attr('rel') ?? "" );
                if(!empty($href) && is_string($href) && Str::startsWith($href, self::MATCHES)) {
                    if($rel === 'stylesheet') {
                        $results[] = $href;
                        $urls = $this->checkStyles($href);
                        if(!empty($urls)) {
                            array_push($results, ...$urls);
                        }
                    }
                }
            }
            $this->consoleLog(json_encode($results, JSON_PRETTY_PRINT));
            return $results;
        } catch (\Throwable $e) {}
        return false;
    }

    private function checkStyles($url): ?array {
        $urls = [];
        if(empty($url)) return $urls;
        try {
            $res = $this->httpClient()->get($url)->getBody()->getContents();
            $links = $this->allLinksFromString($res);
            if(!empty($links)) {
                foreach ($links as $link) {
                    if(is_string($link) &&
                        Str::startsWith(strtolower($link), self::MATCHES) &&
                        Str::endsWith(strtolower($link), self::FONT_EXTENSIONS) &&
                        !in_array($link, $urls)
                    ) {
                        $urls[] = $link;
                    }
                }
            }
        } catch (\Throwable $e) {
            $this->consoleLog("ERROR -->\n" . $e->getMessage());
        }
        return array_unique($urls);
    }



}
