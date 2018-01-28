<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Loader.php
 * Date: 2018/1/27
 * Time: 18:09
 */

namespace frame;


use function Composer\Autoload\includeFile;

class Loader
{
    private static $class_map = [];

    private static $namespace_alias = [];

    /**
     * 注册自动加载机制
     * @access public
     * @param  callable $autoload 自动加载处理方法
     * @return void
     */
    public static function register($autoload = null)
    {
        // spl_autoload_register函数创建了 autoload 函数的队列，按定义时的顺序逐个执行
        spl_autoload_register($autoload ?: 'static::autoload', true, true);

        is_dir(VENDOR_PATH.'composer') and include VENDOR_PATH.'autoload.php';

        static::addNamespace([
            'frame'=>CORE_PATH
        ]);
    }

    public static function addClassMap($class, $path = '')
    {
        if(is_array($class)){
            static::$class_map = array_merge(static::$class_map, $class);
        }else{
            static::$class_map[$class] = $path;
        }
    }

    public static function addNamespace($namespace, $alias = '')
    {
        if(is_array($namespace)){
            static::$namespace_alias = array_merge(static::$namespace_alias, $namespace);
        }else{
            static::$namespace_alias[$namespace] = $alias;
        }
    }

    public static function autoload($class)
    {
        if ($file = self::findFile($class)) {
            // 非 Win 环境不严格区分大小写
            if (pathinfo($file, PATHINFO_FILENAME) == pathinfo(realpath($file), PATHINFO_FILENAME)) {
                includeFile($file);
                return true;
            }
        }

        return false;
    }

    private static function findFile($class)
    {
        if (!empty(self::$class_map[$class])) {
            return self::$class_map[$class];
        }

        $class_name = str_replace('\\', '/', $class);
        $namespace = dirname($class_name);

        if(!empty(static::$namespace_alias[$namespace])){
            return static::$namespace_alias[$namespace].basename($class_name).'.php';
        }

        return self::$class_map[$class] = false;
    }
}