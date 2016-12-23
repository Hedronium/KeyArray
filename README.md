# KeyArray
Allowing you to use arrays as keys for associative arrays.
_Only supports flat arrays of scalar values._

## Installation
```bash
composer require hedronium/key-array
```

## Usage
Just Instantiate the class, or call the `KeyArray::array()` method to
instantiate it.

```PHP
use Hedronium\KeyArray\KeyArray;

$arr = new KeyArray;

// or
$arr = KeyArray::array();
```

then proceed to use it like a normal array.

```PHP
$arr[[]]              = 'The void in my heart.';
$arr[['a']]           = 'AYY';
$arr[['b']]           = 'BEE';
$arr[['a', 'b']]      = 'AYY-BEE';
$arr[['a', 'b', 'c']] = 'AYY-BEE-CEE';
```

iteration with `foreach` works too.

```PHP
foreach ($arr as $key => $val) {
	echo str_pad(implode(' -> ', $key), 20, ' ', STR_PAD_LEFT);
	echo ' = ';
	echo $val;
	echo PHP_EOL;
}

////// OUTPUT: /////////////////////////
//             = The void in my heart.
//           a = AYY
//      a -> b = AYY-BEE
// a -> b -> c = AYY-BEE-CEE
//           b = BEE
```
