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

	public function summary() {
		if (php_sapi_name() === 'cli') {
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
			$total_len += strlen($name);
			if (strlen($name) > $max_len) {
				$max_len = strlen($name);
			}
		}

		##############################################

		print str_repeat(" ",$max_len + 4);
		print join(" | ",$test_names) . "\n";
		$bar = str_repeat(" ",$max_len + 2) . str_repeat("-",$total_len + 10) . "\n";
		print $bar;

		foreach($test_names as $y_name) {
			printf(" %{$max_len}s |",$y_name);
			foreach ($test_names as $x_name) {
				$x_val     = $this->results['count'][$x_name];
				$y_val     = $this->results['count'][$y_name];
				$percent   = round(($y_val / $x_val) * 100,2) . "%";
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
				printf(" %s = %d interations per second\n",$name,$count);

				if ($this->show_differences && ($ret !== $expected_results)) {
					print "    ** Return value from this function differs from the first test **\n";
				}

				$print_r_output = trim(print_r($ret,true));

				$print_r_output = preg_replace("/^/m","       ",$print_r_output);

				print "    Sample output: \n$print_r_output\n\n";
			} else {
				printf("%{$max_len}s = %d interations per second\n",$name,$count);
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
		$out .= "<table style=\"border-collapse: collapse; border: 1px solid black;\">\n";
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
