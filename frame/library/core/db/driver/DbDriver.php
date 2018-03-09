<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Driver.php
 * Date: 2018/3/6
 * Time: 11:13
 */

namespace frame\db\driver;


abstract class DbDriver
{
    abstract public function parseDsn($config);
}