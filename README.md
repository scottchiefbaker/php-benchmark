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


## Sample text output:

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

## Sample HTML output:
<h4>PHP Version: 7.1.14</h4>
<table style="border-collapse: collapse; border: 1px solid black; width: 100%;">
        <tr>
                <td style="white-space: nowrap; text-align: center; font-weight: bold; background-color: #CCE6FF; border: 1px solid black; width: 10em;">&nbsp;</td>
                <td style="white-space: nowrap; text-align: center; font-weight: bold; background-color: #CCE6FF; border: 1px solid black; width: 10em;">trim</td>
                <td style="white-space: nowrap; text-align: center; font-weight: bold; background-color: #CCE6FF; border: 1px solid black; width: 10em;">rtrim + ltrim</td>
                <td style="white-space: nowrap; text-align: center; font-weight: bold; background-color: #CCE6FF; border: 1px solid black; width: 10em;">find + substr</td>
                <td style="white-space: nowrap; text-align: center; font-weight: bold; background-color: #CCE6FF; border: 1px solid black; width: 10em;">regexp</td>
        </tr>
        <tr>
                <td style="white-space: nowrap; background-color: #CCE6FF; font-weight: bold; text-align: right; border: 1px solid black; width: 10em;">trim</td>
                <td style="white-space: nowrap; background-color: #FF9999; font-weight: normal; text-align: center; border: 1px solid black; width: 10em;"><b>n/a</b></td>
                <td style="white-space: nowrap; background-color: white; font-weight: normal; text-align: center; border: 1px solid black; width: 10em;">108.76%</td>
                <td style="white-space: nowrap; background-color: white; font-weight: normal; text-align: center; border: 1px solid black; width: 10em;">611.23%</td>
                <td style="white-space: nowrap; background-color: white; font-weight: normal; text-align: center; border: 1px solid black; width: 10em;">782.93%</td>
        </tr>
        <tr>
                <td style="white-space: nowrap; background-color: #CCE6FF; font-weight: bold; text-align: right; border: 1px solid black; width: 10em;">rtrim + ltrim</td>
                <td style="white-space: nowrap; background-color: white; font-weight: normal; text-align: center; border: 1px solid black; width: 10em;">91.94%</td>
                <td style="white-space: nowrap; background-color: #FF9999; font-weight: normal; text-align: center; border: 1px solid black; width: 10em;"><b>n/a</b></td>
                <td style="white-space: nowrap; background-color: white; font-weight: normal; text-align: center; border: 1px solid black; width: 10em;">561.99%</td>
                <td style="white-space: nowrap; background-color: white; font-weight: normal; text-align: center; border: 1px solid black; width: 10em;">719.86%</td>
        </tr>
        <tr>
                <td style="white-space: nowrap; background-color: #CCE6FF; font-weight: bold; text-align: right; border: 1px solid black; width: 10em;">find + substr</td>
                <td style="white-space: nowrap; background-color: white; font-weight: normal; text-align: center; border: 1px solid black; width: 10em;">16.36%</td>
                <td style="white-space: nowrap; background-color: white; font-weight: normal; text-align: center; border: 1px solid black; width: 10em;">17.79%</td>
                <td style="white-space: nowrap; background-color: #FF9999; font-weight: normal; text-align: center; border: 1px solid black; width: 10em;"><b>n/a</b></td>
                <td style="white-space: nowrap; background-color: white; font-weight: normal; text-align: center; border: 1px solid black; width: 10em;">128.09%</td>
        </tr>
        <tr>
                <td style="white-space: nowrap; background-color: #CCE6FF; font-weight: bold; text-align: right; border: 1px solid black; width: 10em;">regexp</td>
                <td style="white-space: nowrap; background-color: white; font-weight: normal; text-align: center; border: 1px solid black; width: 10em;">12.77%</td>
                <td style="white-space: nowrap; background-color: white; font-weight: normal; text-align: center; border: 1px solid black; width: 10em;">13.89%</td>
                <td style="white-space: nowrap; background-color: white; font-weight: normal; text-align: center; border: 1px solid black; width: 10em;">78.07%</td>
                <td style="white-space: nowrap; background-color: #FF9999; font-weight: normal; text-align: center; border: 1px solid black; width: 10em;"><b>n/a</b></td>
        </tr>
</table><br /><div><b>trim</b> = 2,603,898 iterations per second</div><div><b>rtrim + ltrim</b> = 2,394,144 iterations per second</div><div><b>find + substr</b> = 426,013 iterations per second</div><div><b>regexp</b> = 332,585 iterations per secon</div>
