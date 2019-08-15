<?php

namespace roberthucks\SEOMagic\Containers;

abstract class AbstractArrayAccess implements \ArrayAccess
{
    protected $data;
    
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function __get($key)
    {
        return $this[$key];
    }

    public function __set($key, $val)
    {
        $this[$key] = $val;
    }
}