<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Db.php
 * Date: 2018/2/28
 * Time: 16:37
 */

namespace frame;


use frame\db\driver\Mysql;

class Db
{
    private static $instance;

    public static function connect($config = [])
    {
        if(!isset($instance)){
            static::$instance = new Mysql($config);
        }
        return static::$instance;
    }


}