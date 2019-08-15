<?php

namespace roberthucks\SEOMagic\Traits;

trait ValidatesContainers
{
    public function valid(): bool
    {
        return ! in_array(null, $this->data, true);
    }
}