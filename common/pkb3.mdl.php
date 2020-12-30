<?php

class pkb3
{
	function __construct()
	{
		global $cfg;

		$this->content_dir = $cfg['content_dir'];
		$this->cdl = strlen($this->content_dir);
		$this->tree = [];
		$this->directories = [];
		$this->results = [];
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
		$words = explode('-', $filename);
		// upcase each word
		foreach ($words as $word) {
			$components[] = ucfirst($word);
		}
		// recombine the words
		$str = implode(' ', $components);
		return $str;
	}

	# this is based on a video:
	# https://www.youtube.com/watch?v=GxqgcXmbVgs&list=PLEqwkdbwCxDsHOOLzc-Aj5AO_H4ZXO_ug&index=16
	# and appears to be a lot simpler than my prior code on the subject

	function show_tree($tree)
	{
		$markup = '';

		foreach ($tree as $branch => $twig) {
			$markup .= '<li>' . ((is_array($twig)) ? get_title_from_filename(pathinfo($branch, PATHINFO_FILENAME)) . show_tree($twig) : make_link($twig)) . '</li>';
		}

		return '<ul>' . $markup . '</ul>';
	}

	/**
	 * file_tree()
	 *
	 * Takes a directory and recursively grabs the files and directories
	 * under it. Directories point to arrays of files. Does not return a
	 * value. Instead, it sets the class member $tree.
	 *
	 * @param string $path The path to search
	 *
	 */

	private function file_tree($path)
	{
		$tree = [];

		$files = scandir($path);
		sort($files, SORT_STRING | SORT_FLAG_CASE);

		foreach ($files as $file) {
			if ($file == '..' || $file == '.' || (strpos($file, '.') === 0)) {
				continue;
			}

			if (is_dir($path . '/' . $file)) {
				$tree[$path . '/' . $file] = $this->file_tree($path . '/' . $file);
			}
			else {
				$tree[] = $path . '/' . $file;
			}

		}

		return $tree;
	}

	/**
	 * directory_link()
	 *
	 * Creates a link suitable for the sidebar of a directory
	 *
	 * @param string $path The directory
	 *
	 * @return string A link to the directory.
	 */

	private function directory_link($path)
	{
		return '<a class="dirlink" href="index.php?tree=' . $path . '">' . $this->get_title_from_filename(pathinfo($path, PATHINFO_FILENAME)) . ' ></a>' . PHP_EOL;
	}

	/**
	 * file_link()
	 *
	 * Creates a link suitable for the sidebar of a file
	 *
	 * @param string $path The file
	 *
	 * @return string A link to the file
	 */

	private function file_link($path)
	{
		return '<a class="filelink" href="show.php?page=' . $path . '">' . $this->get_title_from_filename(pathinfo($path, PATHINFO_FILENAME)) . '</a>' . PHP_EOL;
	}

	/**
	 * sidebar()
	 *
	 * Returns a string of list items/links to files/directories
	 *
	 * @param string $subdir The directory to show
	 * @return string Links/anchors to the files in the subdir
	 */

	private function sidebar($subdir)
	{
		$str = '';
		$str .= '<ul>' . PHP_EOL;

		if ($subdir != $this->content_dir) {
			$before = substr($subdir, 0, strrpos($subdir, DIRECTORY_SEPARATOR));
			$str .= '<li><a href="index.php?tree=' . $before . '">< Back</a></li>' . PHP_EOL;
		}

		foreach ($this->tree as $branch => $twig) {
			if (is_array($twig)) {
				$str .= '<li>' . $this->directory_link($branch) . '</li>';
			}
			else {
				$str .= '<li>' . $this->file_link($twig) . '</li>';
			}
		}
		$str .= '</ul>' . PHP_EOL;

		return $str;
	}

	function get_sidebar($subdir)
	{
		$this->tree = $this->file_tree($subdir);
		return $this->sidebar($subdir);
	}

	function parent_sidebar($filepath)
	{
		$before = substr($filepath, 0, strrpos($filepath, DIRECTORY_SEPARATOR));
		return $this->get_sidebar($before);
	}

	function get_content($page)
	{
		global $common_dir;

		$ext = pathinfo($page, PATHINFO_EXTENSION);
		if ($ext == 'md') {
			include $common_dir . 'Parsedown.php';
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
		elseif ($ext == 'pdf') {
			header('Content-Type: application/pdf');
			return file_get_contents($page);
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

	function only_dirs($path)
	{
		$tree = [];

		$files = scandir($path);
		sort($files, SORT_STRING | SORT_FLAG_CASE);

		foreach ($files as $file) {
			if ($file == '..' || $file == '.' || (strpos($file, '.') === 0)) {
				continue;
			}

			if (is_dir($path . '/' . $file)) {
				$tree[] = $path . '/' . $file;
				$this->file_tree($path . '/' . $file);
			}

		}

		return $tree;
	}

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
		$this->dirs($this->content_dir);
		return $this->directories;
	}

	/**
	 * update_title()
	 *
	 * Change the title of a file, which means changing the name
	 * of the file as well.
	 *
	 * @param array The POST array
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

	function add_node($post)
	{
		$basename = $this->create_filename($post['title']);
		$fullname = $post['parent'] . DIRECTORY_SEPARATOR . $basename;
		$ext = $post['extension'];
		$filename = $fullname . '.' . $ext;

		file_put_contents($filename, $post['content']);

		return $filename;
	}

	function delete_node($page)
	{
		unlink($page);
		emsg('S', 'Page successfully deleted');
	}

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
	 * search_dir()
	 *
	 * Search directory for files with content matching the search
	 * criteria. THIS FUNCTION IS RECURSIVE. This function updates the
	 * "results" member array.
	 *
	 * @param string $dir Directory to search in
	 * @param string $search_for Term to find
	 *
	 */ 

	private function search_dir($dir, $search_for)
	{
		foreach ($dir as $branch => $twig) {
			if (is_array($twig)) {
				// dive into the directory
				$this->search_dir($twig, $search_for);
			}
			elseif ($this->search_file($twig, $search_for)) {
				$this->results[] = ['filename' => $twig, 'title' => $this->get_title_from_filename(pathinfo($twig, PATHINFO_FILENAME))];
			}
		}
	}

	/**
	 * get_search_results()
	 *
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
		$this->search_dir($this->tree, $search_for);
		return $this->results;
	}

	function version()
	{
		return 3.2;
	}
}

