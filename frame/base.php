<?php
namespace frame;

// 所有经过defined判断的常量均可以在index.php中修改
define('DS', DIRECTORY_SEPARATOR);

// 核心目录路径常量
defined('FRAME_PATH') or define('FRAME_PATH', ROOT_PATH.'frame'.DS);
define('LIB_PATH', FRAME_PATH.'library'.DS);
define('CORE_PATH', LIB_PATH.'core'.DS);
define('TRAITS_PATH', LIB_PATH.'traits'.DS);

defined('APP_PATH') or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . DS);
defined('CONF_PATH') or define('CONF_PATH', ROOT_PATH.'config'.DS);
defined('VENDOR_PATH') or define('VENDOR_PATH', ROOT_PATH . 'vendor' . DS);

define('IS_CLI', PHP_SAPI == 'cli' ? true : false);

include CORE_PATH.'Loader.php';

Loader::register();

Config::set();
