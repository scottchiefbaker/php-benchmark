<?PHP

require(dirname(__FILE__) . "/../benchmark.class.php");

$a = function($ids) {
	$ids = array_diff($ids,array(87));

	//assert(sizeof($ids) === 999);
};

$b = function($ids) {
	$id = array_search(87,$ids); // Find the ID of where 14 is
	if ($id !== false) {
		array_splice($ids,$id,1);    // Remove that ID only (length 1)
	}

	//assert(sizeof($ids) === 999);
};

$c = function($ids) {
	if (($i = array_search(87, $ids)) !== false) {
		unset($ids[$i]);
	}

	//assert(sizeof($ids) === 999);
};

$d = function($ids) {
	foreach ($ids as $key => $value) {
		if ($value == 87) {
			unset($ids[$key]);
		}
	}

	//assert(sizeof($ids) === 999);
};

$e = function($ids) {
	$ids = array_filter($ids,function($i) {
		return $i != 87;
	});

	//assert(sizeof($ids) === 99);
};

$f = function($ids) {
	$size = sizeof($ids);
	for ($i = 0; $i < $size; $i++) {
		if ($ids[$i] == 87) {
			unset($ids[$i]);
		}
	}

	//assert(sizeof($ids) === 99);
};

$ids = range(1,100);
shuffle($ids);

$bm = new benchmark;
$bm->time_this('array_diff()',$a,[$ids]);
$bm->time_this('array_splice()',$b,[$ids]);
$bm->time_this('array_search() + unset()',$c,[$ids]);
$bm->time_this('foreach() + unset()',$d,[$ids]);
$bm->time_this('array_filter()',$e,[$ids]);
$bm->time_this('for loop',$f,[$ids]);

$bm->summary();
