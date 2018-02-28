<?php
/**
 * Created by PhpStorm.
 * User: xqq
 * Date: 2018/1/28
 * Time: 18:54
 */

namespace app\index\controller;

use app\index\model\User;
use frame\Db;
use frame\Model;

class Index
{
    public function index()
    {
        $model = new User();
        dump($model->where("id=10")->select());
    }
}