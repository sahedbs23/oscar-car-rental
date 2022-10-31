<?php
namespace App\Helpers;

class ReadConfig
{
    /**
     * Cache result.
     * @var array
     */
    private static array $cache = [];

    /**
     * @param string $configName
     * @param string $key
     * @return mixed
     */
    public static function config(string $configName, string $key) : mixed
    {
        if (array_key_exists($configName,  self::$cache)){
            return is_array(self::$cache[$configName]) &&
            array_key_exists($key, self::$cache[$configName])
                ? self::$cache[$configName][$key]
                : null;
        }

        $config = include __DIR__."/../config/$configName.php";
        self::$cache[$configName] = $config;
        return is_array($config) && array_key_exists($key, $config) ? $config[$key] : null;
    }
}