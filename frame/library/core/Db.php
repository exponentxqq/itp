<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Db.php
 * Date: 2018/2/28
 * Time: 16:37
 */

namespace frame;


use frame\db\driver\DbDriver;
use frame\db\exception\DbException;

class Db
{
    private static $instance;

    /**
     * @param array $config
     * @return mixed
     * @throws DbException
     */
    public static function connect($config = [])
    {
        $config = array_merge(Config::get('database'), $config);
        if(!isset($instance)){
            try{
                $type = $config['type'];
                $class = "frame\\db\\driver\\".ucwords($type);

                /** @var DbDriver $driver*/
                $driver = new $class();
                static::$instance = new \PDO($driver->parseDsn($config), $config['username'], $config['password']);
            }catch (\Exception $e){
                throw new DbException('数据库连接失败');
            }
        }
        return static::$instance;
    }
}