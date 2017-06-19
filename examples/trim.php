<?PHP

require(dirname(__FILE__) . "/../benchmark.class.php");

//////////////////////////////////////////////////////////////

$bm = new benchmark;
$bm->include_sample_output = 0;
$bm->show_differences      = 1; // Show differences in the function return

$str = "\t This is a test string\n   \r";

$a = function($i) {
	return trim($i);
};

$b = function($i) {
	return preg_replace("/^\s*|\s*$/","",$i);
};

$c = function($str) {
    $len = strlen($str);

    for ($i = 0; $i < $len; $i++) {
        $char = substr($str,$i,1);

        if ($char === " " || $char === "\t" || $char === "\n" || $char === "\r" || $char === "\0" || $char === "\x0B") {
            continue;
        } else {
            break;
        }
    }

    $start = $i;

    for ($i = $len - 1; $i > 0; $i--) {
        $char = substr($str,$i,1);

        if ($char === " " || $char === "\t" || $char === "\n" || $char === "\r" || $char === "\0" || $char === "\x0B") {
            continue;
        } else {
            break;
        }
    }

    $last     = $i + 1;
    $total    = $last - $start;

    return substr($str,$start,$total);
};

$d = function($i) {
	return rtrim(ltrim($i));
};

// Run the benchmark on each function
$bm->time_this('trim',$a,[$str]);
$bm->time_this('regexp',$b,[$str]);
$bm->time_this('find + substr',$c,[$str]);
$bm->time_this('rtrim + ltrim',$c,[$str]);

// // Print out the summary of the benchmarks we've run
$bm->summary();
