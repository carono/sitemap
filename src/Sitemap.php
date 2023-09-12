<?php

namespace carono\sitemap;

use GuzzleHttp\Client;

class Sitemap
{
    protected static $_client;

    protected static function getClient()
    {
        if (static::$_client) {
            return static::$_client;
        }
        $client = new Client();
        return static::$_client = $client;
    }

    public static function getContent($url)
    {
        $client = static::getClient();
        return $client->get($url)->getBody()->getContents();
    }

    protected static function contentToXml($content)
    {
        $xml = simplexml_load_string($content);
        if ($xml && $namespaces = $xml->getNamespaces()) {
            foreach ($namespaces as $key => $namespace) {
                if (!$key) {
                    $xml->registerXPathNamespace('c', $namespace);
                }
            }
        }
        return $xml;
    }

    public static function getExpandUrls($url)
    {
        $result = [];
        foreach (static::getUrls($url) as $url) {
            $result[] = strpos($url, '.xml') === false ? [$url] : static::getUrls($url);
        }
        return array_merge(...$result);
    }

    public static function getUrls($url)
    {
        $content = static::getContent($url);
        $xml = static::contentToXml($content);
        $locs = $xml->xpath('//c:loc');
        $urls = array_map(function ($loc) {
            return (string)$loc;
        }, $locs);
        return array_unique($urls);
    }
}