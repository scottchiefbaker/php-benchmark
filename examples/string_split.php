<?PHP

require(dirname(__FILE__) . "/../benchmark.class.php");

//////////////////////////////////////////////////////////////

$bm = new benchmark;

$str = "four.score.and.seven.years.ago.our.forefathers.set.forth";

$a = function($i) {
	return explode(".", $i);
};

$b = function($i) {
	return preg_split("/\./", $i);
};

// Run the benchmark on each function
$bm->time_this('explode',$a,[$str]);
$bm->time_this('preg_split',$b,[$str]);

// // Print out the summary of the benchmarks we've run
$bm->summary();
