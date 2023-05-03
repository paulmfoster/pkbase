<?php

class pkbase
{
    public $content_dir, $tree, $directories, $results;

	function __construct()
	{
		global $cfg;

		$this->content_dir = $cfg['content_dir'];
		$this->tree = [];
		$this->directories = [];
		$this->results = [];
	}

	/**
	 * Scan a directory and build a tree array. 
	 *
	 * A tree array is a list
	 * of filenames, directory flags, titles, and timestamps. Check each
	 * file for title metadata. If present, use that as the title.
	 * Otherwise, derive it from the file name.
	 *
	 * @param string The directory to scan
	 * @return array Sum of timestamps, tree array
	 */

	function scan_dir($dir)
	{
		$tree = [];
		$sum = 0;

		$files = scandir($dir);
		$max = count($files);

		for ($i = 0; $i < $max; $i++) {
			if ($files[$i] == '..' || $files[$i] == '.' || (strpos($files[$i], '.') === 0)) {
				continue;
			}
            // omit vim backup files
            if (strpos($files[$i], '~') == strlen($files[$i]) - 1) {
                continue;
            }

			if (is_dir($dir . DIRECTORY_SEPARATOR . $files[$i])) {
				$tree[$i]['isdir'] = 'D';
				$title = $this->get_title_from_filename($files[$i]);
			}
			else {
				$tree[$i]['isdir'] = 'F';
				# $title = $this->fetch_metadata($dir . DIRECTORY_SEPARATOR . $files[$i]);
				# if (empty($title)) {
					$title = $this->get_title_from_filename($files[$i]);
			
				# }
			}
			
			$tree[$i]['title'] = $title;

			$tree[$i]['filename'] = $dir . DIRECTORY_SEPARATOR . $files[$i];
			$stat = stat($dir . DIRECTORY_SEPARATOR . $files[$i]);
			$mtime = $stat['mtime'];
			$sum = bcadd($sum, $mtime);
			$tree[$i]['timestamp'] = $mtime;

		}

		return [$sum, $tree];
	}

	/**
	 * Sort an array of tree records alphabetically
	 *
	 * @param array Tree array
	 * @param array Another tree array
	 * @return integer -1, 0, 1 based on sort order
	 */

	static function sort_titles($a, $b)
	{
		if ($a['title'] < $b['title']) {
			return -1;
		}
		elseif ($a['title'] > $b['title']) {
			return 1;
		}
		else {
			return 0;
		}
	}

    /**
     * Obfuscate filename.
     *
     * Where URLs are like index.php?url=show/page/... , filenames with
     * slashes can confuse the routing. So we "hide" the slashes by
     * converting them to the caret symbol (^).
     *
     * @param string Filename
     * @return string filename with slashes converted
     */

    function hide($filename)
    {
        return str_replace('/', '^', $filename);
    }

    /**
     * De-obfuscate filename.
     *
     * Convert carets (^) in filenames to slashes.
     *
     * @param string Filename
     * @return string filename with carets converted
     */

    function unhide($filename)
    {
        return str_replace('^', '/', $filename);
    }

	/**
	 * Return the string which represents the sidebar
	 *
	 * Checks the passed directory for a map file. If not present, it
	 * builds it by scanning the directory and building one. It calls
	 * other routines for this.
	 *
	 * @param string The directory to show in the sidebar
	 * @return string The HTML sidebar
	 */

	function get_sidebar($dir)
	{
		if (!is_dir($dir)) {
			// get the parent directory
			$dir = substr($dir, 0, strrpos($dir, DIRECTORY_SEPARATOR));
		}

		list($scansum, $scantree) = $this->scan_dir($dir);
		$this->tree = $scantree;

		// sort based on title
		usort($this->tree, ['pkbase', 'sort_titles']);

		$str = '<ul>' . PHP_EOL;

		if ($dir != $this->content_dir) {
			$before = substr($dir, 0, strrpos($dir, DIRECTORY_SEPARATOR));
			$str .= '<li><a href="index.php?url=welcome/index/' . $this->hide($before) . '">&lt; Back</a></li>' . PHP_EOL;
		}

		foreach ($this->tree as $entry) {
			$str .= '<li>';
			if ($entry['isdir'] == 'D') {
				$str .= '<a class="dirlink" href="index.php?url=welcome/index/' . $this->hide($entry['filename']) . '">' . $entry['title'] . ' &gt;</a>';
			}
			else {
				$str .= '<a class="filelink" href="index.php?url=show/page/' . $this->hide($entry['filename']) . '">' . $entry['title'] . '</a>';
			}
			$str .= '</li>' . PHP_EOL;
		}
		$str .= '</ul>' . PHP_EOL;

		return $str;
	}

	/**
	 * Get the title of a file from the tree
     * This routine is used by the show controller.
	 *
	 * @param string The filename
	 * @return string The title if found, or '' if not
	 */

	function get_title($page)
	{
		foreach ($this->tree as $file) {
			if ($file['filename'] == $page) {
				return $file['title'];
			}
		}
		return '';
	}

	/**
	 * get_title_from_filename()
	 *
	 * This routine replaces dashes (-) in filenames, and
	 * uppercases each word in the title. Assumes a filename with no
	 * extension.
	 *
	 * @param string $filename The filename
	 *
	 * @return string The massaged string
	 *
	 */
	
	function get_title_from_filename($filename)
	{
		$pi = pathinfo($filename);
		$filename = $pi['filename'];
		/*
		$words = explode('-', $filename);
		// upcase each word
		foreach ($words as $word) {
			$components[] = ucfirst($word);
		}
		// recombine the words
		$str = implode(' ', $components);
		 */
		$str = str_replace('_', ' ', $filename);
		return $str;
	}

	/**
	 * Get the content from a page and interpret it as needed.
	 *
	 * Content is handled differently, depending on the type of file. In
	 * particular, markdown files are converted to HTML.
	 *
	 * @param string The filename to show
	 * @return string The content, possibly translated
	 */

	function get_content($page)
	{
		global $cfg;

		$ext = pathinfo($page, PATHINFO_EXTENSION);
		switch ($ext) {
		case 'md':
			$pd = load('Parsedown');
			$text = file_get_contents($page);
			$content = $pd->text($text);
			break;
		case 'otl':
			$votl = load('vimoutline', $page);
			$content = $votl->parse();
			break;
		case 'TXT':
		case 'txt':
			$text = file_get_contents($page);
			// mimic line endings in original file
			$content = nl2br($text);
			break;
		case 'HTML':
		case 'HTM':
		case 'htm':
		case 'html':
			$content = file_get_contents($page);
			break;
		case 'jpg':
		case 'JPG':
		case 'jpeg':
		case 'JPEG':
			$stuff = base64_encode(file_get_contents($page));
			$content = '<img src="data:image/jpg;base64,'. $stuff . '">';
			break;
		case 'png':
		case 'PNG':
			$stuff = base64_encode(file_get_contents($page));
			$content = '<img src="data:image/png;base64,'. $stuff . '">';
			break;
		case 'gif':
		case 'GIF':
			$stuff = base64_encode(file_get_contents($page));
			$content = '<img src="data:image/gif;base64,'. $stuff . '">';
			break;
		default:
			$text = file_get_contents($page);
			$content = $text;
			break;
		}

		return $content;
	}

	/**
	 * search_file()
	 *
	 * @param string $file The file in which to search
	 * @param string $search_for That for which to search
	 *
	 * @return boolean Did we find it?
	 */

	private function search_file($file, $search_for)
	{
		$matched = FALSE;

		if (file_exists($file)) {
			$content = file_get_contents($file);
			if ($content !== FALSE) {
				// check to see if this is a regexp
				if (strpos($search_for, '/') === 0) {
					// tests for match; user used regexp for search
					$found = preg_match($search_for, $content);
					// found a file
					if ($found !== 0 && $found !== FALSE) {
						$matched = TRUE;
					}
				}
				else {
					// search case-insensitive in the file
					$found = stripos($content, $search_for);
					if ($found !== FALSE) {
						$matched = TRUE;
					}
				}
			}
		}

		return $matched;
	}

	/**
	 * Get a list of all files at and below the path.
	 *
	 * @param $path
	 */

	private function file_list($path)
	{
		$list = [];
		$files = scandir($path);

		foreach ($files as $file) {
			if ($file == '..' || $file == '.' || (strpos($file, '.') === 0)) {
				continue;
			}
            // omit vim backup files
            if (strpos($file, '~') == strlen($file) - 1) {
                continue;
            }

			if (is_dir($path . '/' . $file)) {
				$list = array_merge($list, $this->file_list($path . '/' . $file));
			}
			else {
				$list[] = $path . '/' . $file;
			}

		}

		return $list;
	}


	/**
	 * Search the "tree" member for files which match the passed search
	 * term.
	 *
	 * @param string $search_for The search term
	 *
	 * @return array The list of files which match
	 *
	 */

	function get_search_results($search_for)
	{
		$matches = [];
		$n = 0;

		// get a list of all the files
		$list = $this->file_list($this->content_dir);

		// iterate over each one
		foreach ($list as $file) {

			// if the file contains the string
			if ($this->search_file($file, $search_for)) {

				// add filename to the match list
				$matches[$n]['filename'] = $file;
				// check for a map for that directory
				$dir = substr($file, 0, strrpos($file, DIRECTORY_SEPARATOR));
				$map = $this->read_map($dir);
				if (empty($map[1])) {
					// no map
					// derive title from filename
					// add title to match list
					$matches[$n]['title'] = $this->get_title_from_filename($file);
				}
				else {
					// there was a map
					foreach ($map[1] as $entry) {
						if ($entry['filename'] == $file) {
							$matches[$n]['title'] = $entry['title'];
							break;
						}
					}
					// if the file wasn't found in the map...
					if (!isset($matches[$n]['title'])) {
						// revert to getting the title from the filename
						$matches[$n]['title'] = $this->get_title_from_filename($file);
					}
				}
				$n++;
			}
		}

		return $matches;
	}

	function version()
	{
		return 5.0;
	}
}

