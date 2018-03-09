<?php
/**
 * Created by PhpStorm.
 * User: xqq
 * Date: 2017/7/29
 * Time: 下午10:59
 */
return [
    'app_debug'        => true,
    'default_timezone' => 'PRC',
    'extra_file_list'  => [FRAME_PATH.'common/helper.php'],

    'default_module'     => 'index',
    'default_controller' => 'index',
    'default_action'     => 'index',
    'deny_module_list'   => ['common'],

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------
    'url_html_suffix'    => 'html',
    'pathinfo_fetch'     => [
        'ORIG_PATH_INFO',
        'REDIRECT_PATH_INFO',
        'REDIRECT_URL',
    ],
    'cache'=>[
        'type'=> 'file',
        'servers'=>[
            [
                'host'=>'',
                'port'=>''
            ]
        ],
    ]
];