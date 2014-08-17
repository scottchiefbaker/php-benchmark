<?PHP

class benchmark {
	public $include_sample_output = 0; // Include sample out in HTML summary
	public $show_differences      = 0; // Highlight if one test has diferent output than the others
	public $test_seconds          = 1; // Time to run each test

	private $results = array(); // Array that will store the results

	public function time_this($name, $func, $args = array()) {
		$seconds = $this->test_seconds;
		$count   = 0;
		$start   = microtime(1);
		$time    = 0;

		while ($time < $seconds) {
			$ret = call_user_func_array($func,$args);

			$time = microtime(1) - $start;
			$count++;
		}

		$this->results['count'][$name]  = intval($count / $seconds);
		$this->results['return'][$name] = $ret;
	}

	public function html_summary() {
		if (sizeof($this->results['count']) === 0) {
			print "<div>No results found</div>";
			return false;
		}

		arsort($this->results['count']);

		$tests = array_keys($this->results['count']);
		array_unshift($tests,'&nbsp;');
		$first = 1;

		$header_color = '#CCE6FF';
		$out = "<table style=\"border-collapse: collapse; border: 1px solid black;\">\n";
		$x = 0;
		foreach ($tests as $name) {
			$out .= "\t<tr>\n";

			if ($first) {
				foreach ($tests as $i) {
					$out .= "\t\t<td style=\"text-align: center; font-weight: bold; background-color: $header_color; border: 1px solid black; width: 10em;\">$i</td>\n";
				}

				$first = 0;
			} else {
				$column = 0;
				$y = 0;
				foreach ($tests as $i) {
					if ($column == 0) {
						$content = $tests[$x + 1];
						$align   = 'right';
						$x++;
						$color = $header_color;
						$fw = 'bold';
					} else {
						$y++;

						$x_name = $tests[$x];
						$y_name = $tests[$y];

						$a = $this->results['count'][$x_name];
						$b = $this->results['count'][$y_name];

						$percentage = sprintf("%.2f%%",($a / $b) * 100);

						if ($x === $y) {
							$content = '<div style="background-color: #FF9999;">N/A</div>';
						} else {
							$content = "$percentage";
						}

						$align   = 'center';
						$color = 'white';
						$fw = 'normal';
					}

					$out .= "\t\t<td style=\"background-color: $color; font-weight: $fw; text-align: $align; border: 1px solid black; width: 10em;\">$content</td>\n";

					$column++;
				}
			}
			$out .= "\t</tr>\n";
		}
		$out .= "</table>";
		$out .= "<br />";

		$slowest = min($this->results['count']);
		$fastest = max($this->results['count']);

		$expected_results = reset($this->results['return']);

		foreach ($this->results['count'] as $test => $speed) {
			if ($speed == $fastest) {
				$extra = "(Fastest)";
			} elseif ($speed == $slowest) {
				$extra = "(Slowest)";
			} else {
				$extra = '';
			}

			$ret = $this->results['return'][$test];

			$out .= "<div><b>$test</b> = $speed iterations per second $extra</div>";

			if ($this->show_differences && ($ret !== $expected_results)) {
				$out .= "<div style=\"margin: 0 0 1em 2em; \"><span style=\"color: red;\"><b>Note:</b></span> Return value from this function differs from the first test</div>\n";
			}

			if ($this->include_sample_output) {
				$out .= "<div style=\"margin: 0 0 1em 2em; \"><b>Sample output:</b> " . print_r($ret,true) . "</div>\n";
			}
		}

		print $out;
	}

	public function reset() {
		$this->results = array();
	}

} // End of class
