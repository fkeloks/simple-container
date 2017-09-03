<?php

namespace SimpleContainer;

/**
 * Class CacheContainer
 * @package SimpleContainer
 */
class CacheContainer
{

    /**
     * @var array Cache
     */
    private static $cache = [];

    /**
     * Push class into cache
     *
     * @param string $key
     * @param $value
     *
     * @return mixed Value
     */
    public static function set(string $key, $value) {
        if (!array_key_exists($key, self::$cache)) {
            self::$cache[$key] = $value;
        }

        return $value;
    }

    /**
     * Get class from key
     *
     * @param $key
     *
     * @return mixed Value
     */
    public static function get($key) {
        return self::$cache[$key];
    }

    /**
     * Check if cache contains key
     *
     * @param $key
     *
     * @return bool TrueOrFalse
     */
    public static function has($key): bool {
        return array_key_exists($key, self::$cache);
    }

}