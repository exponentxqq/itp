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
    private $a = 11;
    public function index()
    {
        $model = new User();
        dump($model->where("id<10")->select());
    }
    
    public function conn()
    {
        try{
            $db = new \PDO('sqlite:'.ROOT_PATH.'sqlite/test.db');
//            $db->exec("create table user(id integer primary key,name text)");
//            $db->query("insert into user VALUES (1, 'zhangsan')");
            $tmtp = $db->prepare('select * from user');
            $tmtp->execute();
            $result = $tmtp->fetchAll();
            dump($result);
        }catch (\Exception $e){
            dump($e->getMessage());
        }
    }

    public function te()
    {
        $arr = range(1,12);
        $page = 0;
        $list_row = 4;
        while($data = array_slice($arr, $page * $list_row, $list_row)){
            // $data就是取到的每一页的数据
            var_dump($data);
            $page++;
        }
    }
}