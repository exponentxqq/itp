<?php
/**
 * Created by PhpStorm.
 * User: xqq
 * Date: 2017/7/29
 * Time: 下午10:59
 */
return [
    'app_debug'=>true,
    'default_timezone'       => 'PRC',

    'default_module'=>'index',
    'default_controller'=>'index',
    'default_action'=>'index',

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------
    'url_html_suffix'        => 'html',
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
];