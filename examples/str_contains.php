<?PHP

require(dirname(__FILE__) . "/../benchmark.class.php");

//////////////////////////////////////////////////////////////

$bm = new benchmark;
$bm->include_sample_output = 0;
$bm->show_differences      = 0; // Show differences in the function return

$str = "\t This is a test string\n   \r";


$a = function($i, $x) {
	$ret = strpos($i, $x) !== false;
	return $ret;
};

$b = function($i, $x) {
	$ret = preg_match("/$x/", $i);
	return $ret;
};

$c = function($i, $x) {
	$ret = str_contains($i, $x);
	return $ret;
};

$d = function($i, $x) {
	$ret = strpbrk($i, $x);
	return $ret;
};

$e = function($i, $x) {
	$ret = strstr($i, $x);
	return $ret;
};

$x = 'g';

// Run the benchmark on each function
$bm->time_this('strpos',$a,[$str, $x]);
$bm->time_this('preg_match',$b,[$str, $x]);
$bm->time_this('str_contains',$c,[$str, $x]);
$bm->time_this('strpbrk',$d,[$str, $x]);
$bm->time_this('strstr',$e,[$str, $x]);

// // Print out the summary of the benchmarks we've run
$bm->summary();
