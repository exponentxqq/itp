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
    private static $file = [];

    public static function run()
    {
        try {
            $config = static::initCommon();
            $request = Request::instance();
            static::$dispatch = empty(static::$dispatch) ? static::routeCheck($request, $config) : static::$dispatch;
            $request->dispatch(static::$dispatch);
            $data = self::exec(static::$dispatch, $config);
        } catch (\Exception $e) {

        }
    }

    public static function initCommon()
    {
        Loader::addNamespace('app', APP_PATH);

        $config = static::init();
        static::$debug = Config::get('app_debug');

        if (!static::$debug) {
            ini_set('display_errors', 'Off');
        } elseif (!IS_CLI) {
            if (ob_get_level() > 0) {
                $output = ob_get_clean();
            }
            ob_start();

            if (!empty($output)) {
                echo $output;
            }
        }

        if (!empty($config['extra_file_list'])) {
            foreach ($config['extra_file_list'] as $file) {
                if (is_file($file) && !isset(static::$file[$file])) {
                    include $file;
                    static::$file[$file] = true;
                }
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

        if (is_dir(CONF_PATH . $module . 'extra')) {
            $dir = CONF_PATH . $module . 'extra';
            $files = scandir($dir);
            foreach ($files as $file) {
                if ('.' . pathinfo($file, PATHINFO_EXTENSION) == CONF_EXT) {
                    $filename = $dir . DS . $file;
                    Config::load($filename, pathinfo($file, PATHINFO_FILENAME));
                }
            }
        }

        return Config::get();
    }

    public static function routeCheck(Request $request, array $config)
    {
        $path = $request->path();

        $result = Route::dispatch($path);

        return $result;
    }

    public static function exec($dispatch, $config)
    {
        $module = strip_tags(strtolower($dispatch['route'][0] ?: $config['default_module']));
        $available = false;
        if (!in_array($module, $config['deny_module_list'])) {
            $available = true;
        }
        $request = Request::instance();

        if ($module && $available) {
            $request->module($module);
            static::init($module);
        }

        $controller = empty($dispatch['route'][1]) ? $config['default_controller'] : strip_tags($dispatch['route'][1]);
        $controller = ucfirst(strtolower($controller));

        $action = empty($dispatch['route'][2]) ? $config['default_action'] : strip_tags($dispatch['route'][2]);
        $action = ucfirst(strtolower($action));

        $request->controller($controller)->action($action);
        $instance = Loader::controller($controller);

        $call = [$instance, $action];

        return static::invokeMethod($call);
    }

    public static function invokeClass($class, $var = [])
    {
        $reflect = new \ReflectionClass($class);

        //        $constructor = $reflect->getConstructor();
        return $reflect->newInstanceArgs($var);
    }

    public static function invokeMethod($method, $var = [])
    {
        if (is_array($method)) {
            $class = is_object($method[0]) ? $method[0] : static::invokeClass($method[0]);
            $reflect = new \ReflectionMethod($class, $method[1]);

            return $reflect->invokeArgs($class, $var);
        }
        exit('方法不存在');
    }
}