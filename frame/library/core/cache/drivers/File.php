<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: File.php
 * Date: 2018/3/9
 * Time: 11:46
 */

namespace frame\cache\drivers;

use frame\cache\Driver;

class File extends Driver
{
    protected $options = [
        'expire'        => 0,
        'cache_subdir'  => true,
        'prefix'        => '',
        'path'          => CACHE_PATH,
        'data_compress' => false,
    ];

    protected function init()
    {
        if (substr($this->options['path'], -1) != DS) {
            $this->options['path'] .= DS;
        }
        // 创建项目缓存目录
        if (!is_dir($this->options['path'])) {
            if (mkdir($this->options['path'], 0755, true)) {
                return true;
            }
        }
        return false;
    }

    protected function key($name)
    {
        $name = md5($name);
        if ($this->options['cache_subdir']) {
            $name = substr($name, 0, 2) . DS . substr($name, 2);
        }
        if ($this->options['prefix']) {
            $name = $this->options['prefix'] . DS . $name;
        }
        $filename = $this->options['path'] . $name . '.php';
        $dir = dirname($filename);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $filename;
    }

    public function has($name)
    {
        return $this->get($name) ? true : false;
    }

    public function get($name, $default = false)
    {
        $filename = $this->key($name);
        if (!is_file($filename)) {
            return $default;
        }
        $content = file_get_contents($filename);
        if (false !== $content) {
            $expire = (int)substr($content, 8, 12);
            if (0 != $expire && time() > filemtime($filename) + $expire) {
                return $default;
            }
            $content = substr($content, 31);
            if ($this->options['data_compress'] && function_exists('gzcompress')) {
                //启用数据压缩
                $content = gzuncompress($content);
            }
            $content = unserialize($content);
            return $content;
        } else {
            return $default;
        }
    }

    public function set($name, $value, $expire = null)
    {
        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }
        if ($expire instanceof \DateTime) {
            $expire = $expire->getTimestamp() - time();
        }
        $filename = $this->key($name);
        $data = serialize($value);
        if ($this->options['data_compress'] && function_exists('gzcompress')) {
            $data = gzcompress($data, 3);
        }
        $data = "<?php\n//" . sprintf("%012d", $expire) . "\nexit();?>\n" . $data;
        $result = file_put_contents($filename, $data);
        if ($result) {
            clearstatcache();
            return true;
        } else {
            return false;
        }
    }

    public function inc($name, $step = 1)
    {
        if($this->has($name)){
            $value = $this->get($name) + $step;
        }else{
            $value = $step;
        }

        return $this->set($name, $value) ? $value : false;
    }

    public function dec($name, $step = 1)
    {
        if($this->has($name)){
            $value = $this->get($name) - $step;
        }else{
            $value = -$step;
        }

        return $this->set($name, $value) ? $value : false;
    }

    public function rm($name)
    {
        $filename = $this->key($name);
        return is_file($filename) && unlink($filename);
    }

    public function clear($tag = null)
    {
        $root_path = $this->options['path'];
        $files = (array)glob($root_path. ($this->options['prefix'] ? $this->options['prefix'] . DS : '') . '*');
        foreach ($files as $path){
            if(is_dir($path)){
                $matches = glob($path.'/*.php');
                if(is_array($matches)){
                    array_map('unlink', $matches);
                }
                rmdir($path);
            }else{
                unlink($path);
            }
        }
        return true;
    }
}