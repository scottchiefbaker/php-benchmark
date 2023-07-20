<?PHP

require(dirname(__FILE__) . "/../benchmark.class.php");

//////////////////////////////////////////////////////////////

$bm = new benchmark;
$bm->include_sample_output = 0;
$bm->show_differences      = 1; // Show differences in the function return

$a = function($arr, $x) {
	array_shift($arr);

	$ret = array_merge([$x], $arr);
	return $ret;
};

$b = function($arr, $x) {
	$arr[0] = $x;

	return $arr;
};

$c = function($arr, $x) {
	$new = array_slice($arr, 1);
	$ret = array_merge([$x], $new);

	return $ret;
};

$d = function($x) {
	// Test here
};

// Run the benchmark on each function
$input = [[2, 4, 6], 9];

$bm->time_this('array_shift', $a, $input);
$bm->time_this('overwrite_first', $b, $input);
$bm->time_this('array_slice', $c, $input);
//$bm->time_this('test4', $d, $input);

// // Print out the summary of the benchmarks we've run
$bm->summary();
