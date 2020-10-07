<?php

class pkb2
{
	function __construct($db)
	{
		global $cfg;

		$this->db = $db;
		$this->toc_file = $cfg['toc_file'];
		$this->content_dir = $cfg['content_dir'];
		$this->suffix = $cfg['suffix'];

		$this->fetch_records();
		$this->make_tree();

	}

	///////////////////////////////////////////////////////////////
	// Routines to generate class variables
	///////////////////////////////////////////////////////////////

	/**
	 * fetch_records()
	 *
	 * Fetch records from the database.
	 * This is only used where a database is actually in use.
	 *
	 * Records will look like the following, more or less:
	 * +--------+------+-------+
	 * | parent | name | title |
	 * +--------+------+-------+
	 *
	 */

	function fetch_records()
	{
		$sql = "SELECT * FROM tree ORDER BY name";
		$this->recs = $this->db->query($sql)->fetch_all();
		$this->max = count($this->recs);
	}

	/**
	 * treeify()
	 *
	 * Creates a branch structure from a flat parent record.
	 * Only called by make_tree().
	 *
	 * Records will look like the following, more or less:
	 * +--------+------+-------+---------------------+
	 * | parent | name | title | children (optional) |
	 * +--------+------+-------+---------------------+
	 *
	 * The children field will be an array of records like above,
	 * if present.
	 *
	 * @param array $row A row from a flat record array
	 *
	 */

	private function treeify($row)
	{
		$nbr = 0;

		for ($i = 0; $i < $this->max; $i++) {
			// is this record a child of $row?
			if ($this->recs[$i]['parent'] == $row['name']) {

				// echo $this->recs[$i]['name'] . ' is a child of ' . $row['name'] . '<br/>';
				
				$this->tree[] = $this->recs[$i];
				$nbr++;
				$this->treeify($this->recs[$i]);
			
			}
		}

		$children = [];
		for ($j = 0; $j < $nbr; $j++) {
			array_unshift($children, array_pop($this->tree));
		}

		if (!empty($children)) {
			$this->tree[count($this->tree) - 1]['children'] = $children;
		}

	}

	/**
	 * make_tree()
	 *
	 * This function turns the flat records into a "treeified"
	 * array.
	 * Calls treeify()
	 *
	 */

	function make_tree()
	{
		$this->tree[] = $this->recs[0];
		$this->treeify($this->recs[0]);
	}

	///////////////////////////////////////////////////////////////
	// Show index (left page panel)
	///////////////////////////////////////////////////////////////

	/**	
	 * webify_title()
	 *
	 * Replaces spaces in titles with the web-safe alternative.
	 * Used when including titles in links.
	 *
	 * @param string $title
	 * @return string The "fixed" title
	 *
	 */

	function webify_title($title)
	{
		return str_replace(' ', '%20', $title);
	}

	/**
	 * show_branch()
	 *
	 * For any branch of the tree, show it.
	 * Called only by show_tree().
	 *
	 * @param array $rec The record to start at.
	 *
	 */

	private function show_branch($rec)
	{
		if (isset($rec['children'])) {

			if ($rec['name'] != $this->content_dir) {
			echo '<li>' . PHP_EOL;
			echo $rec['title'] . PHP_EOL;
			echo '<ul>' . PHP_EOL;
			}

			foreach ($rec['children'] as $child) {
				$this->show_branch($child);
			}

			if ($rec['name'] != $this->content_dir) {
			echo '</ul>' . PHP_EOL;
			echo '</li>' . PHP_EOL;
			}
		}
		else {

			echo '<li>' . PHP_EOL;

			if (is_file($rec['name'] . '.md')) {
				echo '<a href="show.php?page=' . $rec['name'] . '&title=' . $this->webify_title($rec['title']) . '">' . $rec['title'] . '</a>' . PHP_EOL;
			}
			else {
				echo $rec['title'] . PHP_EOL;
			}

			// echo $rec['name'] . PHP_EOL;
			echo '</li>' . PHP_EOL;
		}
	}

	/**
	 * show_tree()
	 *
	 * Prints the index of files
	 * Calls show_branch().
	 *
	 */

	function show_index()
	{
		echo '<ul>' . PHP_EOL;
		$this->show_branch($this->tree[0]);
		echo '</ul>' . PHP_EOL;
	}
	
	///////////////////////////////////////////////////////////////
	// Rebuild
	///////////////////////////////////////////////////////////////

	/**
	 * get_title_from_filename()
	 *
	 * This routine replaces dashes (-) in filenames, and
	 * uppercases each word in the title.
	 *
	 * @param string $filename The filename
	 *
	 * @return string The massaged string
	 *
	 */
	
	function get_title_from_filename($filename)
	{
		// just the filename, no directories or suffix
		$filename = basename($filename, $this->suffix);
		// break name into words
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
	 * content2recs()
	 *
	 * RECURSIVE.
	 * This function scans the content directory and builds an
	 * indexed array of records, which can then be inserted into
	 * the tree table. This function is RECURSIVE. Only use it in
	 * preparation for repopulating the tree table.
	 *
	 * Records will look like:
	 *
	 * +--------+------+-------+
	 * | parent | name | title |
	 * +--------+------+-------+
	 *
	 * @param array $dir The directory to examine
	 *
	 */

	private function dirs2recs($dir)
	{
		global $base_dir_len;

		$files = scandir($dir);
		sort($files, SORT_STRING | SORT_FLAG_CASE);

		foreach ($files as $key => $value) {
			// skip hidden files, particular vim swap files
			if (strpos($value, '.') === 0) {
				continue;
			}
			$path = realpath($dir . DIRECTORY_SEPARATOR . $value);
			if (!is_dir($path)) {

				// we only want the right kind of files
				if (strpos($path, $this->suffix) !== FALSE) {
					// remove prefixed directories
					$pre_name = substr($path, $base_dir_len);
					// remove suffix
					$name = substr($pre_name, 0, strlen($pre_name) - strlen($this->suffix));
					// find the parent directory
					$posn = strrpos($name, '/');
					$parent = substr($name, 0, $posn);

					$title = $this->get_title_from_filename($name);

					$this->recs[] = [
						'parent' => $parent,
						'name' => $name,
						'title' => $title
					];
				}
			} 
			elseif ($value != "." && $value != "..") {
				
				$name = substr($path, $base_dir_len);
				$posn = strrpos($name, '/');
				$parent = substr($name, 0, $posn);
				$title = $this->get_title_from_filename($name);

				$this->recs[] = [
					'parent' => $parent,
					'name' => $name,
					'title' => $title
				];

				$this->dirs2recs(substr($path, $base_dir_len));
			}
		}

	}

	/**
	 * toc2string()
	 * 
	 * A recursive function.
	 * Using a PHP array derived from a scan of the target directory,
	 * build a new string representing the new table of contents file.
	 *
	 * @param array $arr The PHP array representing the contents
	 * directory
	 * @param integer $level The indentation level
	 * @param string $dir The directory scanned
	 *
	 * @return string The string representing the TOC file.
	 *
	 */

	private function toc2string($arr, $level)
	{
		foreach ($arr as $rec) {
			
			if (isset($rec['children'])) {
				$this->str .= str_repeat("\t", $level) . '* ' . $rec['title'] . PHP_EOL;
				$this->toc2string($rec['children'], $level + 1);
			}
			else {
				// indents
				$this->str .= str_repeat("\t", $level);
				// line item adornment
				$this->str .= '* ' . '[[';
				// remove suffix and full filename
				$this->str .= $rec['name'];
				// separator
				$this->str .= '|';
				// title
				$this->str .= $rec['title'];
				// end of line item
				$this->str .= ']]' . PHP_EOL;
			}
		
		}
	}

	/**
	 * rewrite_toc()
	 *
	 * Uses the $tree class array to generate and write a table
	 * of contents file to disc.
	 *
	 */

	private function rewrite_toc()
	{
		$this->str = '';
		if (isset($this->tree[0]['children'])) {
			$this->toc2string($this->tree[0]['children'], 0);
		}
		file_put_contents($this->toc_file, $this->str);
		unset($this->str);
	}

	/**
	 * rebuild_table()
	 *
	 * Takes the array from content2recs() and creates a tree
	 * table from it.
	 */

	private function rebuild_table()
	{
		$sql = 'DROP TABLE IF EXISTS tree';
		$this->db->query($sql);

		$sql = 'CREATE TABLE tree (id integer primary key autoincrement, isdir boolean default 0, name varchar(255), parent varchar(255), title varchar(50))';
		$this->db->query($sql);

		foreach ($this->recs as $rec) {
			if (is_dir($rec['name'])) {
				$rec['isdir'] = 1;
			}
			$this->db->insert('tree', $rec);
		}

	}

	/**
	 * rebuild()
	 *
	 * Uses all the routines in this section of the class to
	 * rebuild the internal arrays ($recs, $tree), repopulate the
	 * tree table (on disc), and rewrite the table of contents
	 * file.
	 *
	 * Starts with a scan of the content directory.
	 */

	function rebuild()
	{
		unset($this->recs);
		
		$title = $this->get_title_from_filename($this->content_dir);
		$this->recs[] = ['parent' => '', 'name' => $this->content_dir, 'title' => $title];
		$this->tree = [];

		echo 'Recreating records array from scanning content directory<br/>';
		$this->dirs2recs($this->content_dir);
		$this->max = count($this->recs);
		
		echo 'Recreating tree array from processing records array<br/>';
		$this->make_tree();
		
		echo 'Rebuilding table<br/>';
		$this->rebuild_table();
		
		echo 'Rebuilding table of contents file<br/>';
		$this->rewrite_toc();

		echo 'Rebuild COMPLETE.<br/>';
	}

	///////////////////////////////////////////////////////////////
	// Probably obsolete routines
	///////////////////////////////////////////////////////////////

	/**
	 * toc_from_dirs()
	 *
	 * Recursive function to return a PHP tree array from a scan of the
	 * target directory.
	 *
	 * @param string $dir directory to scan
	 * @param array &$items the array being built/added to
	 *
	 * @return array The resulting array
	 *
	 */

	// fixme: this may be unfit for use
	//
	function toc_from_dirs($dir, &$items = array())
	{
		global $base_dir_len;

		$files = scandir($dir);
		sort($files, SORT_STRING | SORT_FLAG_CASE);

		foreach ($files as $key => $value) {
			// skip hidden files, particular vim swap files
			if (strpos($value, '.') === 0) {
				continue;
			}
			$path = realpath($dir . DIRECTORY_SEPARATOR . $value);
			if (!is_dir($path)) {
				$items[$dir][] = substr($path, $base_dir_len);
			} 
			elseif ($value != "." && $value != "..") {
				$this->toc_from_dirs(substr($path, $base_dir_len), $items[$dir]);
			}
		}

		return $items;
	}

	///////////////////////////////////////////////////////////////
	// Utility routines
	///////////////////////////////////////////////////////////////

	/**
	 * get_file()
	 *
	 * Get the file to edit.
	 *
	 * @param string $filename The file to edit.
	 * @return string The file to edit.
	 */

	function get_file($filename)
	{
		$contents = file_get_contents($filename);
		return $contents;
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

	/**
	 * get_dirs()
	 *
	 * Gets all the directories within the content directory.
	 * This is used to determine where to add nodes
	 *
	 * @return array Directories found under content
	 */

	function get_dirs()
	{
		$dirs = [];

		foreach ($this->recs as $rec) {
			if (is_dir($rec['name'])) {
				$dirs[] = $rec['name'];
			}
		}

		return $dirs;
	}

	///////////////////////////////////////////////////////////////
	// Major functions
	///////////////////////////////////////////////////////////////

	/**
	 * random_file()
	 *
	 * Check TOC and grab a random filename
	 *
	 * @return string The random filename
	 */

	function random_file()
	{
		// if the only record in $recs is the 'content' directory
		// record, the user hasn't created any content yet!
		if ($this->max == 1) {
			return 1;
		}

		$found = FALSE;

		while (!$found) {
			// get a random page
			$index = array_rand($this->recs);
			// is it a file?
			$isfile = file_exists($this->recs[$index]['name'] . $this->suffix);
			if (!$isfile) {
				// if it's not a file, is it a directory?
				$isdir = is_dir($this->recs[$index]['name']);
				if (!$isdir) {
					// file doesn't exist and it's not a directory
					// this means the index is out of sync with
					// the content directory
					return 2;
				}
				// it is a directory, so search again
			}
			else {
				$found = TRUE;
			}
		}

		return ['name' => $this->recs[$index]['name'], 'title' => $this->recs[$index]['title']];
	}

	function add_node($post)
	{
		$basename = $this->create_filename($post['title']);
		$fullname = $post['parent'] . DIRECTORY_SEPARATOR . $basename;
		$filename = $fullname . $this->suffix;

		file_put_contents($filename, $post['content']);
		$rec = [
			'name' => $fullname,
			'parent' => $post['parent'],
			'title' => $post['title']
		];

		$this->db->insert('tree', $rec);
	}

	function update_node($post)
	{
		$basepage = $post['page'];

		$sql = "SELECT parent FROM tree WHERE name = '$basepage'";
		$rec = $this->db->query($sql)->fetch();

		if ($post['title'] != $post['newtitle']) {

			// title changed, so change the filename
			$basenewname = $rec['parent'] . DIRECTORY_SEPARATOR . $this->create_filename($post['newtitle']);
			$newpage = $basenewname . $this->suffix;
			$oldpage = $post['page'] . $this->suffix;

			unlink($oldpage);	
			$this->db->update('tree', ['name' => $basenewname, 'title' => $post['newtitle']], "name = '{$post['page']}'");
		}
		else {
			$newpage = $post['page'] . $this->suffix;
		}

		file_put_contents($newpage, $post['content']);

		emsg('S', 'Page successfully updated');

	}

	function delete_node($page)
	{
		$filename = $page . $this->suffix;

		unlink($filename);

		$this->db->delete('tree', "name = '$page'");

		emsg('S', 'Page successfully deleted');
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
		$results = [];

		foreach ($this->recs as $rec) {
			// the $recs array stores directories, too
			if (file_exists($rec['name'] . $this->suffix)) {
				$file_content = file_get_contents($rec['name'] . $this->suffix);
				if ($file_content !== FALSE) {

					// check to see if this is a regexp
					if (strpos($search_for, '/') === 0) {
						// tests for match; user used regexp for search
						$found = preg_match($search_for, $file_content);
						// found a file
						if ($found !== 0 && $found !== FALSE) {
							$results[] = $rec;
						}
					}
					else {
						// search case-insensitive in the file
						$found = stripos($file_content, $search_for);
						if ($found !== FALSE) {
							$results[] = $rec;
						}
					}
				}
			}
		}

		return $results;
	}

	function add_topic($parent, $dir)
	{
		// massage directory
		$basename = $this->create_filename($dir);
		// build the full directory name
		$dirname = $parent . DIRECTORY_SEPARATOR . $basename;
		// create the actual directory
		mkdir($dirname);
		// create the database record
		$title = $this->get_title_from_filename($basename);
		$rec = [
			'parent' => $parent,
			'name' => $dirname,
			'title' => $title
		];

		$this->db->insert('tree', $rec);

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

		// delete all the children of that directory
		$this->db->delete('tree', "parent = '$dir'");

		// delete the directory itself from tree
		$this->db->delete('tree', "name = '$dir'");

		emsg('S', 'Topic and all children deleted');
	}

	function version()
	{
		return 2.0;
	}

}

