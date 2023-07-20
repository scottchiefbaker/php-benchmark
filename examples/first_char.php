<?PHP

require(dirname(__FILE__) . "/../benchmark.class.php");

//////////////////////////////////////////////////////////////

$bm = new benchmark;
$bm->include_sample_output = 0;
$bm->show_differences      = 0; // Show differences in the function return

$a = function($h, $s) {
	$ret = str_starts_with($h, $s);

	return $ret;
};

$b = function($h, $s) {
	$c = $h[0];
	$ret = ($s === $c);

	return $ret;
};

$c = function($h, $s) {
	$c = substr($h, 0, 1);
	$ret = ($s === $c);

	return $ret;
};

$d = function($i) {
	// Test here
};

// Run the benchmark on each function
$input = ['hello world', 'h'];

$bm->time_this('str_starts_with', $a, $input);
$bm->time_this('char_compare', $b, $input);
$bm->time_this('substr', $c, $input);
//$bm->time_this('test4', $d, $input);

// // Print out the summary of the benchmarks we've run
$bm->summary();
