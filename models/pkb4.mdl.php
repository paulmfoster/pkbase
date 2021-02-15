<?php

class pkb4
{
	function __construct()
	{
		global $cfg;

		$this->content_dir = $cfg['content_dir'];
		$this->tree = [];
		$this->directories = [];
		$this->results = [];
		$this->metalines = 1;
		$this->separator = '^';
	}

	// fetches metadata (title) from the file specified
	/**
	 * Fetch metadata (title) from the file specified
	 *
	 * @param string The filename to scan
	 * @return string The filename
	 *
	 */

	function fetch_metadata($file)
	{
		$title = '';
		if (file_exists($file)) {
			$lines = file($file);
			for ($i = 0; $i < $this->metalines; $i++) {
				$meta = $lines[$i];
				if (strpos($meta, '[title]:- "') !== FALSE) {
					$exp = explode('"', $meta);
					$title = $exp[1];
				}
			}
		}
		return $title;
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

			if (is_dir($dir . DIRECTORY_SEPARATOR . $files[$i])) {
				$tree[$i]['isdir'] = 'D';
				$title = $this->get_title_from_filename($files[$i]);
			}
			else {
				$tree[$i]['isdir'] = 'F';
				$title = $this->fetch_metadata($dir . DIRECTORY_SEPARATOR . $files[$i]);
				if (empty($title)) {
					$title = $this->get_title_from_filename($files[$i]);
				}
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

		list($mapsum, $maptree) = $this->read_map($dir);
		list($scansum, $scantree) = $this->scan_dir($dir);
		if ($mapsum == 0 || ($mapsum != $scansum)) {
 			$this->write_map($dir, $scantree);
			$this->tree = $scantree;
		}
		else {
			$this->tree = $maptree;
		}

		// sort based on title
		usort($this->tree, ['pkb4', 'sort_titles']);

		$str = '<ul>' . PHP_EOL;

		if ($dir != $this->content_dir) {
			$before = substr($dir, 0, strrpos($dir, DIRECTORY_SEPARATOR));
			$str .= '<li><a href="index.php?tree=' . $before . '">&lt; Back</a></li>' . PHP_EOL;
		}

		foreach ($this->tree as $entry) {
			$str .= '<li>';
			if ($entry['isdir'] == 'D') {
				$str .= '<a class="dirlink" href="index.php?tree=' . $entry['filename'] . '">' . $entry['title'] . ' &gt;</a>';
			}
			else {
				$str .= '<a class="filelink" href="show.php?page=' . $entry['filename'] . '">' . $entry['title'] . '</a>';
			}
			$str .= '</li>' . PHP_EOL;
		}
		$str .= '</ul>' . PHP_EOL;

		return $str;
	}

	/**
	 * Read the map file.
	 *
	 * @param string The directory to search for the map file
	 * @return array Sum of timestamps and tree array
	 */

	function read_map($dir)
	{
		$tree = [];
		$sum = 0;
		$mapfile = $dir . DIRECTORY_SEPARATOR . '.map';
		if (file_exists($mapfile)) {
			$lines = file($mapfile);
			foreach ($lines as $line) {
				$attribs = explode($this->separator, $line);
				$isdir = $attribs[1];
				$tree[] = [
					'title' => $attribs[0],
					'isdir' => $attribs[1],
					'filename' => $attribs[2],
					'timestamp' => $attribs[3]
				];
				$sum += (int) $attribs[3];
			}
		}
		return [$sum, $tree];
	}

	/**
	 * Write the map file from a list of the files in a directory.
	 *
	 * @param string The directory to write the map file to
	 * @param array List of directories/files
	 */

	private function write_map($dir, $tree)
	{
		$str = '';
		foreach ($tree as $branch) {
			$str .= $branch['title'] . 
				$this->separator . 
				$branch['isdir']. 
				$this->separator . 
				$branch['filename'] . 
				$this->separator . 
				$branch['timestamp'] . PHP_EOL;
		}
		file_put_contents($dir . DIRECTORY_SEPARATOR . '.map', $str);
	}

	/**
	 * Get the title of a file from the tree
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
		$words = explode('-', $filename);
		// upcase each word
		foreach ($words as $word) {
			$components[] = ucfirst($word);
		}
		// recombine the words
		$str = implode(' ', $components);
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
		if ($ext == 'md') {
			include $cfg['libdir'] . 'Parsedown.php';
			$pd = new Parsedown;
			$content = file_get_contents($page);
			$text = $pd->text($content);
			return $text;
		}
		elseif ($ext == 'otl') {
			$text = file_get_contents($page);
			// mimic indentations in original file
			$text = str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $text);
			// mimic line endings in original file
			$content = nl2br($text);
			return $content;
		}
		elseif ($ext == 'txt') {
			$text = file_get_contents($page);
			// mimic line endings in original file
			$content = nl2br($text);
			return $content;
		}
		elseif ($ext == 'html') {
			$content = file_get_contents($page);
			return $content;
		}
		else {
			$text = file_get_contents($page);
			$content = $text;
			return $content;
		}
	}

	/**
	 * Get the directories under the path.
	 *
	 * Recursive function called by get_dirs().
	 *
	 * @param string The path to scan
	 *
	 */

	private function dirs($path)
	{
		$files = scandir($path);
		sort($files, SORT_STRING | SORT_FLAG_CASE);

		foreach ($files as $file) {
			if ($file == '..' || $file == '.' || (strpos($file, '.') === 0)) {
				continue;
			}

			if (is_dir($path . '/' . $file)) {
				$this->directories[] = $path . '/' . $file;
				$this->dirs($path . '/' . $file);
			}
		}
	}

	/**
	 * get_dirs()
	 *
	 * Get the directories under the content dir. Used in adding nodes.
	 *
	 * @return array Subdirs of content dir
	 */

	function get_dirs()
	{
		$this->directories[] = $this->content_dir;
		$this->dirs($this->content_dir);
		return $this->directories;
	}

	/**
	 * Create a filename from a title.
	 *
	 * @param array The POST array
	 * @return string The filename
	 *
	 */

	function create_filename($title)
	{
		// define "punctuation"
		$punct = array('~', '`', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '+', '=', '{', '}', '|', '[', ']', '\\', ':', '"', ';', ',', '<', '>', '?', '\'', '.');
		// remove all punctuation
		$filename = str_replace($punct, '', $title);
		// convert slashes to spaces (errors otherwise)
		$filename = str_replace('/', ' ', $filename);
		// convert spaces to dashes (hyphens)
		$filename = str_replace(' ', '-', $filename);
		// lowercase the whole thing
		$filename = strtolower($filename);

		return $filename;
	}

	/**
	 * Add a content page
	 *
	 * @param array POST array
	 * @return string The filename
	 */

	function add_node($post)
	{
		$basename = $this->create_filename($post['title']);
		$fullname = $post['parent'] . DIRECTORY_SEPARATOR . $basename;
		$ext = $post['extension'];
		$filename = $fullname . '.' . $ext;

		$metadata = '[title]:- "' . $post['title'] . '"' . PHP_EOL;

		file_put_contents($filename, $metadata . $post['content']);

		return $filename;
	}

	/**
	 * Delete a content page
	 *
	 * @param string The filename to delete
	 */

	function delete_node($page)
	{
		unlink($page);
		emsg('S', 'Page successfully deleted');
	}

	/**
	 * Make changes to a content file.
	 *
	 * @param array The POST array
	 */

	function update_node($post)
	{
		$title = $this->get_title_from_filename(pathinfo($post['page'], PATHINFO_FILENAME));

		if ($title != $post['newtitle']) {

			$directory = pathinfo($post['page'], PATHINFO_DIRNAME);
			$old_ext = pathinfo($post['page'], PATHINFO_EXTENSION);
			$new_ext = $post['extension'];
			$basename = $this->create_filename($post['newtitle']);

			$new_filename = $directory . DIRECTORY_SEPARATOR . $basename . '.' . $new_ext;

			unlink($post['page']);
			file_put_contents($new_filename, $post['content']);

		}
		else {
			file_put_contents($post['page'], $post['content']);
		}

		emsg('S', 'Page successfully updated');
	}

	/**
	 * Create a new directory
	 *
	 * @param string Parent of the directory to create
	 * @param string The directory to create
	 */

	function add_topic($parent, $dir)
	{
		// massage directory
		$basename = $this->create_filename($dir);
		// build the full directory name
		$dirname = $parent . DIRECTORY_SEPARATOR . $basename;
		// create the actual directory
		mkdir($dirname);

		emsg('S', 'Topic successfully added.');
	}

	/**
	 * Delete a directory (AND ITS FILES)
	 *
	 * @param string The directory to delete
	 */

	function delete_topic($dir)
	{
		// empty the directory first
		$dirs = scandir($dir);
		foreach ($dirs as $link) {
			if ($link != '.' && $link != '..') {
				unlink($dir . DIRECTORY_SEPARATOR . $link);
			}
		}

		// now empty the directory
		rmdir($dir);
		emsg('S', 'Topic and all children deleted');
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
		return 4.0;
	}
}

