<?PHP

require(dirname(__FILE__) . "/../benchmark.class.php");

//////////////////////////////////////////////////////////////////////////////

$str = " Monday  Tuesday Wednesday Thursday Friday\nSaturday\tSunday";

$a = function($str,$find) {
	$ok = strstr($str,$find);
	return boolval($ok);
};

$b = function($str,$find) {
	$ok = strpos($str,$find);
	return boolval($ok);
};

$c = function($str,$find) {
	$ok = stristr($str,$find);
	return boolval($ok);
};

$d = function($str,$find){
	$ok = preg_match("/$find/",$str);
	return boolval($ok);
};

////////////(//////////////////////////////////////////////////////////////////

$bm = new benchmark;

$bm->include_sample_output = 0; // Include sample return data in HTML summary
$bm->show_differences      = 1; // Show differences in the function return
$bm->test_seconds          = 3; // Number of seconds to run each test for

$find = "Sat";

// Run the benchmark on each function
// $bm->time_this($name, $anonymous_function, array of arguments to pass);
$bm->time_this('strstr',$a,array($str,$find));
$bm->time_this('strpos',$b,array($str,$find));
$bm->time_this('stristr',$c,array($str,$find));
$bm->time_this('preg_match',$d,array($str,$find));

// Print out the summary of the benchmarks we've run
$bm->summary();
