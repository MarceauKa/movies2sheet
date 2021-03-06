<?php

namespace App\Utils;

class Cache
{
    public static string $path = '';

    public static function has(string $key): bool
    {
        return file_exists(self::path($key));
    }

    public static function get(string $key)
    {
        return file_get_contents(self::path($key));
    }

    public static function set(string $key, $value): bool
    {
        return file_put_contents(self::path($key), $value);
    }

    protected static function path(string $key)
    {
        if (empty(self::$path)) {
            self::$path = base_path('data/cache/');

            if (false === is_dir(self::$path)) {
                mkdir(self::$path);
            }
        }

        return self::$path . str_replace(['\'', ' ', '/', '\\', ':', '&', '?', '=', ',', ';', '[', ']', '(', ')', 'ç', 'à', '+', '&'], '', $key);
    }
}
