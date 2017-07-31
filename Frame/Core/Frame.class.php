<?php
/**
 * Created by PhpStorm.
 * User: xqq
 * Date: 2017/7/9
 * Time: 下午3:29
 */

namespace Core;

class Frame
{
    private static $map = [
        'Core' => FRAME_PATH
    ];

    public static function start()
    {
        // 注册AUTOLOAD方法
        self::getConfig();
        self::setConst();
        spl_autoload_register('self::autoload');
        self::dispatcher();
    }

    public static function getConfig()
    {
        $GLOBALS['conf'] = include FRAME_PATH . 'Config/config.php';
    }

    public static function setConst(){
        //echo FRAME_PATH;
        $p = isset($_GET['m']) ? $_GET['m'] : $GLOBALS['conf']['M'];
        $c = isset($_GET['c']) ? $_GET['c'] : $GLOBALS['conf']['C'];
        //定义项目目录App下的Home下的Controller
        define('CONTROLLER_PATH', APP_PATH . $p . '/Controller/');
        define('MODEL_PATH', APP_PATH . $p . '/Model/');
        define('VIEW_PATH', APP_PATH . $p . '/View/');
        //echo CONTROLLER_PATH;
        //定义Core路径
        define('CORE_PATH', FRAME_PATH . 'Core/');
        //定义配置项目录
        define('CONFIG_PATH', FRAME_PATH . 'Config/');
        //模板目录
        define('TEMP_PATH', VIEW_PATH . $c);
        //编译文件目录
        define('COMP_PATH', APP_PATH . 'Runtime/');
    }

    public static function autoload($class_name)
    {
        $class_name = str_replace('\\','/',$class_name);
        $path = strstr($class_name, '/', true);
        if(isset(self::$map[$path])){
            $filename = self::$map[$path].$class_name.'.class.php';
        }else {
            $filename = APP_PATH . $class_name . '.class.php';
        }
        $filename = str_replace('\\','/',$filename);
        if(file_exists($filename)) {
            $r = include_once $filename;
        }
    }

    private static function getModule(){
        $module   = @$_GET['m'] ? $_GET['m'] : $GLOBALS['conf']['M'];
        define('MODULE_NAME',$module);

        return strip_tags(ucfirst($module));
    }

    private static function getController(){
        $controller = @$_GET['c'] ? $_GET['c'] : $GLOBALS['conf']['C'];
        define('CONTROLLER_NAME',$controller);

        return strip_tags(ucfirst($controller));
    }

    private static function getAction(){
        $action = @$_GET['a'] ? $_GET['a'] : $GLOBALS['conf']['A'];
        define('ACTION_NAME',$action);

        return strip_tags(ucfirst($action));
    }

    /**请求分发方法
     * 获取地址栏里的m参数、c参数、a参数，根据这三个参数，实例化一个控制器类，调用里面的一个方法
     */
    private static function dispatcher(){
        $class = self::getModule().'\\Controller\\'.self::getController().'Controller';
//        $controller = $c . 'Controller';
        $action = self::getAction();
        $obj = new $class();
        $obj->$action();
    }
}