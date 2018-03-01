<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Collection.php
 * Date: 2018/3/1
 * Time: 13:23
 */

namespace frame;

use ArrayAccess;
use Iterator;
use Countable;
use JsonSerializable;

class Collection implements ArrayAccess, Countable, JsonSerializable, Iterator
{
    protected $items = [];
    private $index = 0;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function toArray()
    {
        return $this->items;
    }

    public function current()
    {
        return $this->items[$this->index];
    }

    public function next()
    {
        return ++$this->index;
    }

    public function key()
    {
        return $this->index;
    }

    public function valid()
    {
        return isset($this->items[$this->index]);
    }

    public function rewind()
    {
        return $this->index = 0;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    public function count()
    {
        return count($this->items);
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}