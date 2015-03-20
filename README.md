PHP-Benchmark
=============

Simple library to compare the speed of PHP functions.

Usage:
------

```PHP
require('benchmark.class.php');

$a = function($str) {
	return strtoupper($str);
};

$b = function($str) {
	return strtolower($str);
};

$bm = new benchmark;

// Run the benchmark on each function
// $bm->time_this(name, anonymous function, array of arguments to pass);
$bm->time_this('Convert to Uppercase',$a,array('kittens'));
$bm->time_this('Convert to Lowercase',$b,array('KITTENS'));

// Print out the summary of the benchmarks we've run
$bm->summary();
```
