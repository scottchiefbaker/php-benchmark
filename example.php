<?PHP

require('benchmark.class.php');

//////////////////////////////////////////////////////////////////////////////

$str = " Monday  Tuesday Wednesday Thursday Friday\nSaturday\tSunday";

$a = function($str) {
	return str_word_count($str,1);
};

$c = function($str) {
	return preg_split("/\s+/",trim($str));
};

$d = function($string){
	return array_filter(explode(' ', implode(' ', array_map('trim', explode("\n", $string)))));
};

////////////(//////////////////////////////////////////////////////////////////

$bm = new benchmark;

$bm->include_sample_output = 1; // Include sample return data in HTML summary
$bm->show_differences      = 1; // Show differences in the function return
$bm->test_seconds          = 1; // Number of seconds to run each test for

// Run the benchmark on each function
// $bm->time_this($name, $anonymous_function, array of arguments to pass);
$bm->time_this('str_word_count',$a,array($str));
$bm->time_this('scott_split',$c,array($str));
$bm->time_this('reddit_split',$d,array($str));

// Print out the summary of the benchmarks we've run
$bm->summary();
