<?php

include 'vendor/autoload.php';

use roberthucks\SEOMagic\SEOMagic;

$magic = new SEOMagic();

echo $magic->fetchPage('https://roberthucks.com');

