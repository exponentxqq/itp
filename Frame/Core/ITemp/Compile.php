<?php

/**
 * Created by PhpStorm.
 * User: xqq
 * Date: 2017/7/29
 * Time: 上午10:04
 */
class Compile
{
    private $temp;
    private $content;
    private $comfile;
    private $left = '{';
    private $right = '}';
    private $value = [];

    public function __construct(){}

    public function compile($source, $dest){
        file_put_contents($dest, file_get_contents($source));
    }

    public function c_var(){
        $patten = "#\{\\$([a-zA-Z_\xff][a-zA-Z0-9_\x7f-\xff]*)\}#";
        if(strpos($this->content, '{$') !== false){
            $this->content = preg_replace($patten, "<?php echo \$this->value['\\1']; ?>",$this->content);
        }
    }

}