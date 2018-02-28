<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Mysql.php
 * Date: 2018/2/28
 * Time: 16:59
 */

namespace frame\db\driver;

class Mysql
{
    public function parseDsn($config){
        $dsn = 'mysql:';
        $dsn .= "host={$config['hostname']};";
        $dsn .= "port={$config['hostport']};";
        $dsn .= "dbname={$config['database']};";
        return $dsn;
    }
}