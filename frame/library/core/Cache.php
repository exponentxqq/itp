<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Cache.php
 * Date: 2018/3/9
 * Time: 11:45
 */

namespace frame;


use frame\cache\Driver;

class Cache
{
    private static $instance;
    private static $handler = null;

    /**
     * @param array $option
     * @param bool  $name
     * @return Driver
     */
    public static function connect(array $option = [], $name = false)
    {
        $type = !empty($option['type']) ? $option['type'] : 'File';

        if(false === $name){
            $name = md5(serialize($option));
        }

        if(true === $name || !isset(self::$instance[$name])){
            $class = strpos($type, '\\') ?
                $type : '\\frame\\cache\drivers\\'.ucwords($type);

            if (true === $name) {
                return new $class($option);
            }

            self::$instance[$name] = new $class($option);
        }

        return self::$instance[$name];
    }

    public static function init(array $option = [])
    {
        if(is_null(self::$handler)){
            $config = Config::get('cache');
            if(!empty($option)){
                $config = array_merge($config, $option);
            }
            self::$handler = self::connect($config);
        }
        return self::$handler;
    }

    public static function has($name)
    {
        return self::init()->has($name);
    }

    public static function get($name, $default = false)
    {
        return self::init()->get($name, $default);
    }

    public static function set($name, $value, $expire = null)
    {
        return self::init()->set($name, $value, $expire);
    }

    public static function inc($name, $step = 1)
    {
        return self::init()->inc($name, $step);
    }

    public static function dec($name, $step = 1)
    {
        return self::init()->dec($name, $step);
    }

    public static function rm($name)
    {
        return self::init()->rm($name);
    }

    public static function clear($tag = null)
    {
        return self::init()->clear($tag);
    }

    public static function pull($name)
    {
        return self::init()->pull($name);
    }

    /**
     * @param      $name
     * @param      $value
     * @param null $expire
     * @return mixed
     * @throws \Exception
     * @throws \throwable
     */
    public static function remember($name, $value, $expire = null)
    {
        return self::init()->remember($name, $value, $expire);
    }
}