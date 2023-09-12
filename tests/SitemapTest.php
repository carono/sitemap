<?php


use carono\sitemap\Sitemap;
use PHPUnit\Framework\TestCase;

class SitemapTest extends TestCase
{
    public function testGetUrls()
    {
        $urls = Sitemap::getUrls('https://wordpress.com/sitemap.xml');
        static::assertCount(1, $urls);

        $urls = Sitemap::getExpandUrls('https://wordpress.com/sitemap.xml');
        static::assertGreaterThan(600, count($urls));
    }
}
