<?PHP

require(dirname(__FILE__) . "/../benchmark.class.php");

//////////////////////////////////////////////////////////////

$bm = new benchmark;
$bm->include_sample_output = 0;
$bm->show_differences      = 1; // Show differences in the function return

function get_tokens2($str) {
	$ret   = [];
	$count = 0;
	$pos   = 0;
	while ($pos !== false) {
		$pos = strpos($str, '{', $pos);
		if ($pos !== false) {
			$pair[$count][0] = $pos;
			$pos++;
		}

		if ($count++ > 10000) { break; }
	}

	$pos   = 0;
	$count = 0;
	while ($pos !== false) {
		$pos             = strpos($str, '}', $pos);
		if ($pos !== false) {
			$pair[$count][1] = $pos;
			$pos++;
		}

		if ($count++ > 10000) { break; }
	}

	$last  = 0;
	foreach ($pair as $x) {
		$start = $x[0];
		$end   = $x[1] + 1;

		// If the { } aren't right next to each other, get the stuff in between
		if ($last && $start) {
			$tok = substr($str, $last, $start - $last);
			if ($tok) {
				$ret[] = $tok;
			}
		}

		$tok = substr($str, $start, $end - $start);
		if ($tok) {
			$ret[] = $tok;
		}

		$last = $end;
	}

	return $ret;
}

function get_next_block(&$str) {
	// Pull out each block
	$pos = strpos($str, '}');
	$ret = substr($str, 0, $pos + 1);

	// Update the string to remove this part we found
	$str = substr($str, $pos + 1);

	return $ret;
}

function get_tokens1($str) {
	$parts = [];

	// Get all the blocks
	while ($block = get_next_block($str)) {
		$p    = preg_split("/\{/", $block);
		$p[1] = '{' . $p[1];

		if ($p[0]) {
			$parts[] = $p[0];
		}
		if ($p[1]) {
			$parts[] = $p[1];
		}
	}

	// Remove any empty items
	$parts = array_filter($parts);

	return $parts;
}

function get_tokens3($str) {
	$x = preg_split('/({[^}]+})/', $str, 0, PREG_SPLIT_DELIM_CAPTURE);
	$x = array_filter($x);
	$x = array_values($x);

	return $x;
}

$str = "\t This is a test string\n   \r";
$str = '{if true}yes{else}no{if $x}{$x}{/if}{/if}';

//print_r(get_tokens1($str));
//print_r(get_tokens2($str));
//print_r(get_tokens3($str));
//die;

// Run the benchmark on each function
$bm->time_this('tokens1','get_tokens1',[$str]);
$bm->time_this('tokens2','get_tokens2',[$str]);
$bm->time_this('tokens3','get_tokens3',[$str]);

// // Print out the summary of the benchmarks we've run
$bm->summary();
