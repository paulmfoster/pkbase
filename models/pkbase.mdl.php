<?php

class pkbase
{
	
	var $index, $files_list;

	function __construct()
	{
		global $cfg;

		$filename = $cfg['toc_file'];

		$lines = file($filename, FILE_IGNORE_NEW_LINES);

		$tabs = 0;
		$indent = 0;
		$this->index = '<ul>' . PHP_EOL;
		foreach ($lines as $line) {
			$tabs = substr_count($line, "\t");

			if ($tabs > $indent) {
				$indent = $tabs;
				$this->index .= '<ul>' . PHP_EOL;
			}

			if ($tabs < $indent) {
				for ($i = $tabs; $i < $indent; $i++) {
					$this->index .= '</ul>' . PHP_EOL;
					$this->index .= '</li>' . PHP_EOL;
				}
				$indent = $tabs;
			}

			$link = substr($line, $tabs + 1);

			if (strpos($link, '[[')) {

				$ltrim = substr($link, 3);
				$len = strlen($ltrim);
				$rtrim = substr($ltrim, 0, $len - 2);
				$newlink = explode('|', $rtrim);
				
				$this->index .= '<li>' . PHP_EOL;
				$this->index .= '<a href="show.php?page=' . $newlink[0] . '&title=' . $newlink[1] . '">' . $newlink[1] . '</a>' . PHP_EOL;
				$this->index .= '</li>' . PHP_EOL;

				$this->files_list[] = [$newlink[0], $newlink[1]];
			}
			else {
				$this->index .= '<li>' . PHP_EOL;
				$this->index .= $link . PHP_EOL;
			}

		}

		for ($i = $tabs; $i > 0; $i--) {
			$this->index .= '</ul>' . PHP_EOL;
		}

		$this->index .= '</ul>' . PHP_EOL;
	}

	/**
	 * get_title_from_filename()
	 *
	 * @param string $filename The filename
	 *
	 * @return string or FALSE if not found
	 *
	 */

	function get_title_from_filename($filename)
	{
		foreach ($this->files_list as $link) {
			if ($link[0] == $filename) {
				return $link[1];
			}
		}
		return FALSE;
	}

	/**
	 * random_file()
	 *
	 * Check TOC and grab a random filename
	 *
	 * @return string The random filename
	 */

	function random_file()
	{
		global $cfg;

		$index = array_rand($this->files_list);
		$found = file_exists($this->files_list[$index][0] . $cfg['suffix']);
		if (!$found) {
			return FALSE;
		}

		return $this->files_list[$index];
	}

	/**
	 * get_search_results()
	 *
	 * Find files which match search criteria
	 * Rather than write a recursive function to scan the directories
	 * directly, I just iterate over the $files_list array.
	 *
	 * @param string $search_for Term to find
	 *
	 * @return array Filenames containing the search term
	 *
	 */ 

	function get_search_results($search_for)
	{
		global $cfg;

		$results = [];

		foreach ($this->files_list as $file) {
			$file_content = file_get_contents($file[0] . $cfg['suffix']);
			if ($file_content !== FALSE) {

				// check to see if this is a regexp
				if (strpos($search_for, '/') === 0) {
					// tests for match; user used regexp for search
					$found = preg_match($search_for, $file_content);
					// found a file
					if ($found !== 0 && $found !== FALSE) {
						$results[] = $file;
					}
				}
				else {
					// search case-insensitive in the file
					$found = stripos($file_content, $search_for);
					if ($found !== FALSE) {
						$results[] = $file;
					}
				}
			}
		}

		return $results;
	}

	function version()
	{
		return 1.0;
	}
}

