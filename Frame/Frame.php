<?php
/**
 * Created by PhpStorm.
 * User: xqq
 * Date: 2017/7/9
 * Time: 下午3:09
 *
 * 框架引导文件
 */
//定义框架目录的路径
define('FRAME_PATH', __DIR__.DIRECTORY_SEPARATOR);

include(FRAME_PATH.'Core/Frame.class.php');

Frame::start();