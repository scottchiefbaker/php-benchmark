<?PHP

require(dirname(__FILE__) . "/../benchmark.class.php");

//////////////////////////////////////////////////////////////

$bm = new benchmark;
$bm->include_sample_output = 0;
$bm->show_differences      = 0; // Show differences in the function return

$a = function($x) {
	// Test here
};

$b = function($x) {
	// Test here
};

$c = function($x) {
	// Test here
};

$d = function($x) {
	// Test here
};

// Run the benchmark on each function
$input = [2, 4, 6];

$bm->time_this('test1', $a, $input);
$bm->time_this('test2', $b, $input);
$bm->time_this('test3', $c, $input);
$bm->time_this('test4', $d, $input);

// // Print out the summary of the benchmarks we've run
$bm->summary();
