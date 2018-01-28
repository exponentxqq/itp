<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Request.php
 * Date: 2018/1/27
 * Time: 20:25
 */

namespace frame;


class Request
{
    private static $instance = null;

    protected $path = null;
    protected $pathinfo = null;

    protected $server = [];
    protected $route = [];

    protected $dispatch = [];
    protected $module = '';
    protected $controller = '';
    protected $action = '';

    private function __construct()
    {
    }

    /**
     * @return Request
    */
    public static function instance()
    {
        if (!static::$instance instanceof static) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function path()
    {
        if (is_null($this->path)) {
            $suffix = Config::get('url_html_suffix');
            $pathinfo = $this->pathinfo();
            if (false === $suffix) {
                // 禁止伪静态访问
                $this->path = $pathinfo;
            } elseif ($suffix) {
                // 去除正常的URL后缀
                $this->path = preg_replace('/\.(' . ltrim($suffix, '.') . ')$/i', '', $pathinfo);
            } else {
                // 允许任何后缀访问
                $this->path = preg_replace('/\.' . $this->ext() . '$/i', '', $pathinfo);
            }
        }

        return $this->path;
    }

    public function pathinfo()
    {
        if (is_null($this->pathinfo)) {
            if (IS_CLI) {
                // CLI模式下 index.php module/controller/action/params/...
                $_SERVER['PATH_INFO'] = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : '';
            }

            // 分析PATHINFO信息
            if (!isset($_SERVER['PATH_INFO'])) {
                if (isset($_SERVER['REQUEST_URI'])) {
                    $_SERVER['PATH_INFO'] = explode('?', $_SERVER['REQUEST_URI'])[0];
                } else {
                    foreach (Config::get('pathinfo_fetch') as $type) {
                        if (!empty($_SERVER[$type])) {
                            $_SERVER['PATH_INFO'] = (0 === strpos($_SERVER[$type], $_SERVER['SCRIPT_NAME'])) ? substr($_SERVER[$type], strlen($_SERVER['SCRIPT_NAME'])) : $_SERVER[$type];
                            break;
                        }
                    }
                }
            }
            $this->pathinfo = empty($_SERVER['PATH_INFO']) ? '/' : ltrim($_SERVER['PATH_INFO'], '/');
        }

        return $this->pathinfo;
    }

    public function input($data = [], $name = '', $default = null, $filter = '')
    {
        if (is_array($data)) {
            array_walk_recursive($data, [
                $this,
                'filterValue',
            ], $filter);
            reset($data);
        } else {
            $this->filterValue($data, $name, $filter);
        }

        return $data;
    }

    public function route($name, $default = null, $filter = '')
    {
        if (is_array($name)) {
            $this->route = array_merge($name, $this->route);
        }

        return $this->input($this->route, $name, $default, $filter);
    }

    public function dispatch($dispatch = null)
    {
        if(!is_null($dispatch)){
            $this->dispatch = $dispatch;
            return $this;
        }
        return $this->dispatch;
    }

    public function module($module = null)
    {
        if(!is_null($module)){
            $this->module = $module;
            return $this;
        }
        return $this->module;
    }

    public function controller($controller = null)
    {
        if(!is_null($controller)){
            $this->controller = $controller;
            return $this;
        }
        return $this->controller;
    }

    public function action($action = null)
    {
        if(!is_null($action)){
            $this->action = $action;
            return $this;
        }
        return $this->action;
    }

    public function method()
    {
        return IS_CLI ? 'GET' : (isset($this->server['REQUEST_METHOD']) ? $this->server['REQUEST_METHOD'] : $_SERVER['REQUEST_METHOD']);
    }

    public function isGet()
    {
        return 'GET' === $this->method();
    }

    public function isPost()
    {
        return 'POST' === $this->method();
    }

    private function filterValue(& $value, $key, $filter)
    {
        return $value;
    }
}