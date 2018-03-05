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

	public function summary($type = "") {
		if ($type != "html" && php_sapi_name() === 'cli') {
			$this->text_summary();
		} else {
			$this->html_summary();
		}
	}

	public function text_summary() {
		if (sizeof($this->results['count']) === 0) {
			print "No results found";
			return false;
		}

		$php_version = phpversion();
		print "PHP Version: $php_version\n\n";

		arsort($this->results['count']);

		$test_names = array_keys($this->results['count']);

		$max_len   = 0;
		$total_len = 0;
		foreach ($test_names as $name) {
			// Minimum allowed length for alignment is 8 in text mode
			$len = strlen($name);
			if ($len < 8) {
				$len = 8;
			}

			$total_len += $len;
			if (strlen($name) > $max_len) {
				$max_len = strlen($name);
			}
		}

		##############################################

		print str_repeat(" ",$max_len + 4); // Indent

		$pad_names = $test_names;
		foreach ($pad_names as &$i) {
			$i = sprintf("%8s",$i);
		}
		print join(" | ",$pad_names) . "\n";

		// The length of the bar is the total length of the test names,
		// plus the " | " between each word test pair,
		// plus four because there are two spaces at the start and end
		$additional = (sizeof($test_names) - 1) * 3;
		$bar = str_repeat(" ",$max_len + 2) . "+" . str_repeat("-",$total_len + $additional + 2) . "+\n";
		print $bar;

		foreach($test_names as $y_name) {
			printf(" %{$max_len}s |",$y_name);
			foreach ($test_names as $x_name) {
				$x_val     = $this->results['count'][$x_name];
				$y_val     = $this->results['count'][$y_name];
				$percent   = round(($y_val / $x_val) * 100,2) . "%";

				$x_name = sprintf("%8s",$x_name);
				$col_width = strlen($x_name) + 1;

				if ($y_val == $x_val) {
					$percent = "N/A";
				}

				//printf(" %{$col_width}s",$percent);
				printf("%{$col_width}s |","$percent");
			}

			print "\n";
		}
		print $bar;

		print "\n";
		$max_len += 1;

		$expected_results = reset($this->results['return']);

		//print_r($this->results['count']);
		foreach($test_names as $name) {
			$count = $this->results['count'][$name];
			$ret   = $this->results['return'][$name];

			if ($this->include_sample_output) {
				printf(" %s = %s iterations per second\n",$name,number_format($count));

				if ($this->show_differences && ($ret !== $expected_results)) {
					print "    ** Return value from this function differs from the first test **\n";
				}

				$print_r_output = trim(print_r($ret,true));

				$print_r_output = preg_replace("/^/m","       ",$print_r_output);

				print "    Sample output: \n$print_r_output\n\n";
			} else {
				printf("%{$max_len}s = %s iterations per second\n",$name,number_format($count));
			}
		}
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

		$php_version = phpversion();
		$out = "<h4>PHP Version: $php_version</h4>\n";

		$header_color = '#CCE6FF';
		$out .= "<table style=\"border-collapse: collapse; border: 1px solid black; width: 100%;\">\n";
		$x = 0;
		foreach ($tests as $name) {
			$out .= "\t<tr>\n";

			// If it's the first row, output all the headers
			if ($first) {
				foreach ($tests as $i) {
					$out .= "\t\t<td style=\"white-space: nowrap; text-align: center; font-weight: bold; background-color: $header_color; border: 1px solid black; width: 10em;\">$i</td>\n";
				}

				$first = 0;
			// It's not the first row so loop through outputting each data cell
			} else {
				$column = 0;
				$y = 0;
				foreach ($tests as $i) {
					// If it's the first column, it's the row header (on the left)
					if ($column == 0) {
						$content = $tests[$x + 1];
						$color   = $header_color;
						$align   = 'right';
						$fw      = 'bold';
						$x++;
					} else {
						$y++;

						$x_name = $tests[$x];
						$y_name = $tests[$y];

						$a = $this->results['count'][$x_name];
						$b = $this->results['count'][$y_name];

						$percentage = sprintf("%.2f%%",($a / $b) * 100);

						$color = 'white';
						$align = 'center';
						$fw    = 'normal';

						// Row/Column are the same so we output n/a
						if ($x === $y) {
							$color   = "#FF9999";
							$content = "<b>n/a</b>";
						// It's a legit data point
						} else {
							$content = "$percentage";
						}
					}

					// Output each data cell
					$out .= "\t\t<td style=\"white-space: nowrap; background-color: $color; font-weight: $fw; text-align: $align; border: 1px solid black; width: 10em;\">$content</td>\n";

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
			$ret = $this->results['return'][$test];

			$speed_str = number_format($speed);
			$out .= "<div><b>$test</b> = $speed_str iterations per second</div>";

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
