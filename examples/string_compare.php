<?PHP

require(dirname(__FILE__) . "/../benchmark.class.php");

//////////////////////////////////////////////////////////////

$bm                   = new benchmark;
$bm->show_differences = 1; // Show differences in the function return

$one = "My name is ason Doolis.";
$two = "my name is jason doolis.";

$a = function($one, $two) {
	$ret = (strtolower($one) === strtolower($two));
	return $ret;
};

$b = function($one, $two) {
	$ret = (strcasecmp($one, $two) === 0);
	return $ret;
};

// Run the benchmark on each function
$bm->time_this('===',$a,[$one, $two]);
$bm->time_this('strcasecmp',$b,[$one, $two]);

// // Print out the summary of the benchmarks we've run
$bm->summary();
