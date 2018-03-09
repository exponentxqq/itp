<?php
/**
 * Created by PhpStorm.
 * User: xqq
 * Date: 2018/1/28
 * Time: 18:54
 */

namespace app\index\controller;

use app\index\model\User;
use frame\Cache;
use frame\Config;
use frame\Container;
use frame\Db;
use frame\Model;
use frame\Request;

class Index
{
    public function index()
    {
        dump($this);
        container()->bind('Index', $this);
        dump(\container()->make('Index'));
        $request = container()->make('request');
        dump($request);
        $request = Container::getInstance()->make('request');
        dump($request);
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
//        Cache::set('cache', 'haahaha');
        Cache::clear();
        dump(Cache::get('file'));
        dump(Cache::get('cache'));
    }
}