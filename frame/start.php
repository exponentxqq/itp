<?php
/**
 * Created by PhpStorm.
 * User: xqq
 * Date: 2017/7/9
 * Time: 下午3:09
 *
 * 框架引导文件
 */

namespace frame;

include ROOT_PATH . 'frame/base.php';

$container = Container::getInstance();
$container->bind('request', function (){
    return Request::instance();
});

App::run();