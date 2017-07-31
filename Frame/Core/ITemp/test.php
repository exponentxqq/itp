<?php
/**
 * Created by PhpStorm.
 * User: xqq
 * Date: 2017/7/29
 * Time: 上午10:30
 */
include './ITemp.php';
$tpl = new ITemp(['php_turn'=>true, 'debug'=>true,'cache_htm'=>true]);
$tpl->assign('data','hello world');
$tpl->assign('person','cafeCAT');
$tpl->assign('pai',3.14);
$arr = [1,2,3,'hahattt',6];
$tpl->assign('b',$arr);
$tpl->show('test');