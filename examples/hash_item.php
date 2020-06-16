<?PHP

require(dirname(__FILE__) . "/../benchmark.class.php");

//////////////////////////////////////////////////////////////

$bm = new benchmark;
$bm->include_sample_output = 0;
$bm->show_differences      = 1; // Show differences in the function return

for ($i = 1; $i < 100; $i++) {
	$data[$i * 100] = random_int(10,1000);
}

$a = function($i,$num) {
	$ret = array_slice($i, $num, 1, true);

	return $ret;
};

$b = function($i, $num) {
	$numf = 0;
	$ret  = [];
	foreach ($i as $k => $v) {
		if ($numf === $num) {
			$ret[$k] = $v;

			return $ret;
		}

		$numf++;
	}

	return [];
};

$c = function($i,$num) {
	$key = array_keys($i)[$num] ?? "";
	$val = $i[$key];

	$ret[$key] = $val;

	return $ret;
};

// Run the benchmark on each function
$bm->time_this('array_slice',$a,[$data,3]);
$bm->time_this('foreach',$b,[$data,3]);
$bm->time_this('array_keys',$c,[$data,3]);

// // Print out the summary of the benchmarks we've run
$bm->summary();
