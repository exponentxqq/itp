<?php

/**
 * Created by PhpStorm.
 * User: xqq
 * Date: 2017/7/29
 * Time: 上午10:09
 */
namespace Home\Controller;

use Core\Controller;

class IndexController extends Controller
{
    public function index(){
        dump($_SERVER);
    }

    public function test(){
        $this->assign('data','hello world');
        $this->assign('person','cafeCAT');
        $this->assign('pai',3.14);
        $arr = [1,2,3,'hahattt',6];
        $this->assign('b',$arr);
        $this->show('test');
    }
}