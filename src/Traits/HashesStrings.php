<?php

namespace roberthucks\SEOMagic\Traits;

trait HashesStrings
{
    public function hashString(string $string)
    {
        return sha1($string);
    }
}