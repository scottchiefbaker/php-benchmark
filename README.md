PHP-Benchmark
=============

Simple library to compare the speed of PHP code snippets.

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

## Sample output:
```
PHP Version: 7.1.14

                     trim | rtrim + ltrim | find + substr |   regexp
               +-----------------------------------------------------+
          trim |      N/A |       106.32% |       595.74% |  763.28% |
 rtrim + ltrim |   94.05% |           N/A |       560.31% |  717.88% |
 find + substr |   16.79% |        17.85% |           N/A |  128.12% |
        regexp |    13.1% |        13.93% |        78.05% |      N/A |
               +-----------------------------------------------------+

          trim = 2,544,217 iterations per second
 rtrim + ltrim = 2,392,881 iterations per second
 find + substr = 427,065 iterations per second
        regexp = 333,325 iterations per second
```
