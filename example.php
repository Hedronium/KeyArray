<?php
require 'vendor/autoload.php';

use Hedronium\KeyArray\KeyArray;

$arr = KeyArray::array();

$arr[[]]              = 'The void in my heart.';
$arr[['a']]           = 'AYY';
$arr[['b']]           = 'BEE';
$arr[['a', 'b']]      = 'AYY-BEE';
$arr[['a', 'b', 'c']] = 'AYY-BEE-CEE';

foreach ($arr as $k => $v) {
	echo str_pad(implode(' -> ', $k), 20, ' ', STR_PAD_LEFT), ' = ', $v;
	echo PHP_EOL;
}
