<?PHP

require(dirname(__FILE__) . "/../benchmark.class.php");

//////////////////////////////////////////////////////////////

$bm = new benchmark;
$bm->include_sample_output = 0;
$bm->show_differences      = 0; // Show differences in the function return

$a = function($i) {
	$ret = is_null($i);

	return $ret;
};

$b = function($i) {
	$ret = ($i === null);

	return $ret;
};

// Run the benchmark on each function
$input = null;
$bm->time_this('is_null', $a, [$input]);
$bm->time_this('==='    , $b, [$input]);

// // Print out the summary of the benchmarks we've run
$bm->summary();
