<?php
/**
 * Created by PhpStorm.
 * User: xqq
 * Date: 2018/1/28
 * Time: 18:54
 */

namespace app\index\controller;

use frame\Db;

class Index
{
    public function index()
    {
        dump(Db::connect());
    }
}