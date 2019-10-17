<?php

namespace Spatie\Sitemap;

class SitemapNamespace
{
    public static $namespaces = [
        'xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
        'xmlns:xhtml' => 'http://www.w3.org/1999/xhtml',
    ];

    public static function overrideNamespaces($namespaces = [])
    {
        self::$namespaces = array_merge(self::$namespaces, $namespaces);
    }

    public static function generateNamespaces()
    {
        $result = '';
        foreach (self::$namespaces as $key => $value) {
            $result .= ' '.$key.'="'.$value.'"';
        }

        return $result;
    }

    public static function setDefault()
    {
        self::$namespaces = [
            'xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
            'xmlns:xhtml' => 'http://www.w3.org/1999/xhtml',
        ];
    }
}
