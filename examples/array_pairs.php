<?PHP

require(dirname(__FILE__) . "/../benchmark.class.php");

$a = function($ids) {
	while ($ids) {
		[$x, $y] = array_splice($ids, 0, 2);

		//fwrite(STDERR, "$x, $y\n");
	}
};

$b = function($ids) {
	while ($ids) {
		$x = array_shift($ids);
		$y = array_shift($ids);

		//fwrite(STDERR, "$x, $y\n");
	}
};

$c = function($ids) {
	for ($i = 0; $i < count($ids); $i += 2) {
		$x = $ids[$i];
		$y = $ids[$i+1];

		//fwrite(STDERR, "$x, $y\n");
	}
};

$d = function($ids) {
	$chunks = array_chunk($ids, 2);

	foreach ($chunks as $z) {
		$x = $z[0];
		$y = $z[1];

		//fwrite(STDERR, "$x, $y\n");
	}
};

$input = [1, 5, 7, 9, 12, 5, 7, 8, 4, 4, 5, 5];
//call_user_func_array($c, [$input]);
//print "\n";
//call_user_func_array($d, [$input]);
//die;

$bm = new benchmark;
$bm->time_this('array_splice()', $a, [$input]);
$bm->time_this('array_shift()' , $b, [$input]);
$bm->time_this('C for loop'    , $c, [$input]);
$bm->time_this('array_chunk()' , $d, [$input]);

$bm->summary();
