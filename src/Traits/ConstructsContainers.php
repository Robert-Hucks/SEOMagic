<?php

namespace roberthucks\SEOMagic\Traits;

use roberthucks\SEOMagic\Exceptions\InvalidContainerDataException;

trait ConstructsContainers
{
    public function __construct(array $data = null)
    {
        if (! is_null($data)) {
            foreach ($data as $key => $value) {
                if (! array_key_exists($key, $this->data)) {
                    throw new InvalidContainerDataException('This key is not valid for this container: ' . $key);
                }

                $this->$key = $value;
            }
        }
    }
}