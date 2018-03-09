<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Driver.php
 * Date: 2018/3/9
 * Time: 11:45
 */

namespace frame\cache;

abstract class Driver
{
    protected $handler = null;
    protected $options = [];
    protected $tag;

    abstract public function has($name);

    abstract public function get($name, $default = false);

    abstract public function set($name, $value, $expire = null);

    abstract public function inc($name, $step = 1);

    abstract public function dec($name, $step = 1);

    abstract public function rm($name);

    abstract public function clear($tag = null);

    abstract protected function init();

    public function __construct(array $option = [])
    {
        if(!empty($option)){
            $this->options = array_merge($this->options, $option);
        }
        $this->init();
    }

    public function pull($name){
        $result = $this->get($name);
        if($result){
            $this->rm($name);
        }
        return $result;
    }

    public function remember($name, $value, $expire = null)
    {
        if (!$this->has($name)) {
            $time = time();
            while ($time + 5 > time() && $this->has($name . '_lock')) {
                // 存在锁定则等待
                usleep(200000);
            }

            try {
                // 锁定
                $this->set($name . '_lock', true);
                if ($value instanceof \Closure) {
                    $value = call_user_func($value);
                }
                $this->set($name, $value, $expire);
                // 解锁
                $this->rm($name . '_lock');
            } catch (\Exception $e) {
                // 解锁
                $this->rm($name . '_lock');
                throw $e;
            } catch (\throwable $e) {
                $this->rm($name . '_lock');
                throw $e;
            }
        } else {
            $value = $this->get($name);
        }
        return $value;
    }

    protected function key($name)
    {
        return $this->options['prefix'] . $name;
    }
}