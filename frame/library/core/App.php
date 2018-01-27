<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: App.php
 * Date: 2018/1/27
 * Time: 19:53
 */

namespace frame;

class App
{
    private static $debug = null;
    private static $dispatch = null;

    public static function run()
    {
        try {
            $config = static::initCommon();
            $request = Request::instance();
            static::$dispatch = empty(static::$dispatch) ?: self::routeCheck($request, $config);
            $data = self::exec(static::$dispatch, $config);
        } catch (\Exception $e) {

        }
    }

    public static function initCommon()
    {
        Loader::addNamespace('app', APP_PATH);

        $config = static::init();
        static::$debug = Config::get('app_debug');

        if(!static::$debug){
            ini_set('display_errors', 'Off');
        }elseif (!IS_CLI){
            if(ob_get_level() > 0){
                $output = ob_get_clean();
            }
            ob_start();

            if(!empty($output)){
                echo $output;
            }
        }
        date_default_timezone_set($config['default_timezone']);

        return Config::get();
    }

    public static function init($module = '')
    {
        Config::load(CONF_PATH . $module . 'config' . CONF_EXT);
        $filename = CONF_PATH . $module . 'database' . CONF_EXT;
        Config::load($filename, 'database');

        if(is_dir(CONF_PATH . $module . 'extra')){
            $dir = CONF_PATH.$module.'extra';
            $files = scandir($dir);
            foreach ($files as $file){
                if('.'. pathinfo($file, PATHINFO_EXTENSION) == CONF_EXT){
                    $filename = $dir . DS . $file;
                    Config::load($filename, pathinfo($file, PATHINFO_FILENAME));
                }
            }
        }
        return Config::get();
    }

    public static function routeCheck($request, array $config)
    {

    }
}