<?PHP

require('php-benchmark/benchmark.class.php');
$bm = new benchmark;
$bm->include_sample_output = 0;

$hash = array(
	'name'   => 'Scott Baker',
	'age'    => 35,
	'phones' => array (
		'cell' => 9035467843,
		'home' => 9035663973,
		'work' => 9035665000,
	),
	'likes'  => 'kittens',
	'gender' => 'male',
	'object' => $bm,
);

$a = function($i) {
	return serialize($i);
};

$b = function($i) {
	return json_encode($i);
};

$c = function($i) {
	return var_export($i,true);
};

// Run the benchmark on each function
$bm->time_this('Serialize',$a,[$hash]);
$bm->time_this('JSON Encode',$b,[$hash]);
$bm->time_this('var_export()',$c,[$hash]);

// // Print out the summary of the benchmarks we've run
$bm->summary();
