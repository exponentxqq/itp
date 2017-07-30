<?php

/**
 * Created by PhpStorm.
 * User: xqq
 * Date: 2017/7/29
 * Time: 上午10:04
 */
class Compile
{
    private $temp;              //待编译的文件
    private $content;           //需要替换的文本
    private $comfile;           //编译后的文件
    private $left = '{';        //左定界符
    private $right = '}';       //右定界符
    private $value = [];        //值栈
    private $php_turn;
    private $T_P;
    private $T_R;

    public function __construct($template, $compile_file, $config){
        $this->temp = $template;
        $this->comfile = $compile_file;
        $this->content = file_get_contents($template);
        if($config['php_turn'] === false){
            $this->T_P[] = "#<\?(=|php|)(.+?)\?>#is";
            $this->T_R[] = "&lt;?\\1\\2?&gt;";
        }
        $this->T_P[] = "#\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}#";
        $this->T_P[] = "#\{(loop|foreach)\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)}#i";
        $this->T_P[] = "#\{\/(loop|foreach|if)}#i";
        $this->T_P[] = "#\{([K|V])\}#";
        $this->T_P[] = "#\{if(.*?)\}#i";
        $this->T_P[] = "#\{(else if|elseif)(.*?)\}#i";
        $this->T_P[] = "#\{else\}#i";
        $this->T_P[] = "#\{(\#|\*)(.*?)(\#|\*)\}#";

        $this->T_R[] = "<?php echo \$this->value['\\1']; ?>";
        $this->T_R[] = "<?php foreach((array)\$this->value['\\2'] as \$K=>\$V){ ?>";
        $this->T_R[] = "<?php } ?>";
        $this->T_R[] = "<?php echo \$\\1; ?>";
        $this->T_R[] = "<?php if(\\1){ ?>";
        $this->T_R[] = "<?php }else if(\\2){ ?>";
        $this->T_R[] = "<?php }else{ ?>";
        $this->T_R[] = "";
    }

    public function compile(){
        $this->c_var2();
        $this->c_staticFile();
        file_put_contents($this->comfile, $this->content);
    }

    public function c_var2(){
        $this->content = preg_replace($this->T_P, $this->T_R, $this->content);
    }

    public function c_staticFile(){
        $this->content = preg_replace('#\{\!(.*?)\!\}#','<script src=\\1'.'?t='.time().'></script>',$this->content);
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }


    public function c_var(){
        $patten = "#\{\\$([a-zA-Z_\xff][a-zA-Z0-9_\x7f-\xff]*)\}#";
        if(strpos($this->content, '{$') !== false){
            $this->content = preg_replace($patten, "<?php echo \$this->value['\\1']; ?>",$this->content);
        }
    }

}