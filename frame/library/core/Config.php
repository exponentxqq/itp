<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Config.php
 * Date: 2018/1/27
 * Time: 19:25
 */

namespace frame;

class Config
{
    private static $config = [];

    public static function set($name, $value = '')
    {
        if (is_string($name)) {
            if (!strpos($name, '.')) {
                static::$config[strtolower($name)] = $value;
            } else {
                $name = explode('.', $name, 2);
                static::$config[strtolower($name[0])][$name[1]] = $value;
            }
        }

        if (is_array($name)) {
            if (!empty($value)) {
                static::$config[strtolower($value)] = isset(static::$config[strtolower($value)]) ? array_merge(static::$config[strtolower($value)], $name) : $name;
                return static::$config[strtolower($value)];
            }
            return static::$config = array_merge(static::$config, array_change_key_case($name));
        }
        return static::$config;
    }

    public static function get($name = null)
    {
        if (is_null($name)) {
            return static::$config;
        }
        if (!strpos($name, '.')) {
            return static::$config[$name];
        } else {
            $name = explode('.', $name);
            return static::$config[strtolower($name[0])][$name[1]];
        }
    }

    public static function load($file, $name = '')
    {
        if (is_file($file)) {
            $name = strtolower($name);
            $type = pathinfo($file, PATHINFO_EXTENSION);

            switch ($type) {
                case 'php':
                    return static::set(include $file, $name);
                    break;
                default :

                    break;
            }
        }
        return static::$config;
    }
}