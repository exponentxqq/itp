<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Mysql.php
 * Date: 2018/2/28
 * Time: 16:59
 */

namespace frame\db\driver;

use frame\Config;
use frame\db\exception\DbException;

class Mysql
{
    private $conn = null;

    /**
     * Mysql constructor.
     * @param array $config
     * @throws DbException
     */
    public function __construct($config = [])
    {
        $config = $config ? array_merge(Config::get('database'), $config) : Config::get('database');
        if(is_null($this->conn)){
            try{
                $this->conn = new \PDO($this->parseDsn($config), $config['username'], $config['password']);
            }catch (\Exception $e){
                throw new DbException('数据库连接失败');
            }
        }
        return $this->conn;
    }

    private function parseDsn($config){
        $dsn = 'mysql:';
        $dsn .= "host={$config['hostname']};";
        $dsn .= "port={$config['hostport']};";
        $dsn .= "dbname={$config['database']};";
        return $dsn;
    }

    public function __destruct()
    {
        $this->conn = null;
    }
}