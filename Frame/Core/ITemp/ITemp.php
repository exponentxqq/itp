<?php

/**
 * Created by PhpStorm.
 * User: xqq
 * Date: 2017/7/28
 * Time: 下午11:24
 */
class ITemp
{
    private $config = [
        'suffix'       => '.m',         //设置模版文件的后缀
        'tempDir'      => './temp/',      //设置模版所在的文件夹
        'compileDir'   => './Runtime/',   //设置编译后文件存放的目录
        'cash_htm'     => false,        //是否需要编译成静态的html页面
        'suffix_cache' => '.html',      //设置编译文件的后缀
        'cache_time'   => 7200,         //多长时间自动更新，单位s
    ];

    public $file; //模版文件名，不包含路径

    private static $instance = null;

    private $value = [];

    private $compileTool;   //编译器

    public function __construct($config = [])
    {
        include './Compile.php';
        $this->compileTool = new Compile();
        $this->config = array_merge($this->config, $config);
    }

    function __clone()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setConfig($key, $value)
    {
        if (is_array($key)) {
            $this->config = array_merge($this->config, $key);
        } else {
            $this->config[$key] = $value;
        }
    }

    public function getConfig($key)
    {
        if ($key) {
            return $this->config[$key];
        } else {
            return $this->config;
        }
    }

    public function assign($key, $value = '')
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->value[$k] = $v;
            }
        } else {
            $this->value[$key] = $value;
        }
    }

    public function show($file)
    {
        $this->file = $file;
        if (!is_file($this->path())) {
            exit("模版不存在！");
        }

        $compileFile = $this->config['compileDir'].'/'.md5($file).'.php';
        if(!is_file($compileFile)){
            if(is_dir($this->config['compileDir']))mkdir($this->config['compileDir']);
            $this->compileTool->compile($this->path(), $compileFile);
        }else{
            readfile($compileFile);
        }
    }

    public function path()
    {
        return $this->config['tempDir'] . $this->file . $this->config['suffix'];
    }
}