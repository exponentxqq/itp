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
        'suffix'       => '.m',             //设置模版文件的后缀
        'tempDir'      => './temp/',        //设置模版所在的文件夹
        'compileDir'   => './Runtime/',     //设置编译后文件存放的目录
        'cache_htm'     => false,           //是否需要编译成静态的html页面
        'suffix_cache' => '.htm',           //设置编译文件的后缀
        'cache_time'   => 7200,             //多长时间自动更新，单位s
        'php_turn'     => false,            //是否支持原生php
        'cache_control'=> 'control.dat',
        'debug'        => false

    ];

    public $file; //模版文件名，不包含路径

    private static $instance = null;

    private $value = [];

    private $compileTool;   //编译器

    public $debug = [];
    private $controlData = [];

    public function __construct($config = [])
    {
        $this->debug['begin'] = microtime(true);
        $this->config = array_merge($this->config, $config);
        $this->getPath();
        if(!is_dir($this->config['tempDir'])){
            exit("template dir isn't found");
        }
        if(!is_dir($this->config['compileDir'])){
            mkdir($this->config['compileDir'],0770,true);
        }
        include './Compile.php';
    }

    public function getPath(){
        $this->config['tempDir'] = strtr(realpath($this->config['tempDir']),'\\','/').'/';
        $this->config['compileDir'] = strtr(realpath($this->config['compileDir']),'\\','/').'/';
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setConfig($key, $value = null)
    {
        if (is_array($key)) {
            $this->config = array_merge($this->config, $key);
        } else {
            $this->config[$key] = $value;
        }
    }

    public function getConfig($key = null)
    {
        if ($key) {
            return $this->config[$key];
        } else {
            return $this->config;
        }
    }

    public function assign($key, $value = '')
    {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
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
        $cacheFile = $this->config['compileDir'].md5($file).'.htm';
        if($this->reCache($file)){
            $this->debug['cached'] = 'false';
            $this->compileTool = new Compile($this->path(), $compileFile, $this->config);
            if($this->needCache()) ob_start();
            extract($this->value, EXTR_OVERWRITE);
            if(!is_file($compileFile) || filemtime($compileFile) < filemtime($this->path())){
                $this->compileTool->vars = $this->value;
                $this->compileTool->compile();
                include $compileFile;
            }else{
                include $compileFile;
            }
            if($this->needCache()){
                $message = ob_get_contents();
                file_put_contents($cacheFile, $message);
            }
        }else{
            readfile($cacheFile);
            $this->debug['cached'] = 'true';
        }
        $this->debug['spend'] = microtime(true) - $this->debug['begin'];
        $this->debug['count'] = count($this->value);
        $this->debugInfo();
    }

    public function path()
    {
        return $this->config['tempDir'] . $this->file . $this->config['suffix'];
    }

    public function needCache(){
        return $this->config['cache_htm'];
    }

    public function reCache($file){
        $flag = false;
        $cacheFile = $this->config['compileDir'].md5($file).'.htm';
        if($this->config['cache_htm'] === true){
            $timeFlag = (time() - @filemtime($cacheFile)) < $this->config['cache_time'] ? true : false;
            if(is_file($cacheFile) && filesize($cacheFile) > 1 && $timeFlag){
                $flag = true;
            }else{
                $flag = false;
            }
        }
        return $flag;
    }

    public function debugInfo(){
        if($this->config['debug'] === true){
            $str  = PHP_EOL.'---------------debug info----------------'.PHP_EOL;
            $str .= '程序运行时间：'.date('Y-m-d H:i:s').PHP_EOL;
            $str .= '模版解析耗时：'.$this->debug['spend'].'秒'.PHP_EOL;
            $str .= '模版包含标签数目：'.$this->debug['count'].PHP_EOL;
            $str .= '是否使用静态缓存：'.$this->debug['cached'].PHP_EOL;
            $str .= '模版引擎实例参数：'.var_dump($this->getConfig());
            $str = str_replace(PHP_EOL, '<br>',$str);
            echo $str;
        }
    }

    public function clean($path = null){
        if($path === null){
            $path = $this->config['compileDir'];
            $path = glob($path.'* '.$this->config['suffix_cache']);
        }else{
            $path = $this->config['compileDir'].md5($path).'.htm';
        }

        foreach((array)$path as $v){
            unlink($v);
        }
    }
}