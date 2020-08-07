<?php

include 'init.php';


/**
 * toc_from_dirs()
 *
 * Recursive function to return a PHP array from a scan of the
 * target directory.
 *
 * @param string $dir directory to scan
 * @param array &$items the array being built/added to
 *
 * @return array The resulting array
 *
 */

function toc_from_dirs($dir, &$items = array())
{
	$basedir = basename($dir);
	$files = scandir($dir);
	sort($files, SORT_STRING | SORT_FLAG_CASE);

	foreach ($files as $key => $value) {
		// skip hidden files, particular vim swap files
		if (strpos($value, '.') === 0) {
			continue;
		}
		$path = realpath($dir . DIRECTORY_SEPARATOR . $value);
		if (!is_dir($path)) {
			$base = basename($path);
			$items[$basedir][] = $base;
		} 
		elseif ($value != "." && $value != "..") {
			toc_from_dirs($path, $items[$basedir]);
		}
	}

	return $items;
}

/**
 * labelify()
 *
 * Take a filename, and remove the extraneous characters to create a
 * label for the table of contents
 *
 * @param string $filename filename to manipulate
 *
 * @return string The modified strings
 *
 */

function labelify($filename)
{
	global $cfg;

	$fn1 = str_replace('-', ' ', $filename);
	$fn2 = ucwords($fn1);
	$fn3 = str_replace($cfg['suffix'], '', $fn2);
	return $fn3;
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

function toc2string($arr, $level, $dir)
{
	global $str, $cfg;

	foreach ($arr as $key => $val) {
		if (is_array($val)) {
			$str .= str_repeat("\t", $level) . '* ' . labelify($key) . PHP_EOL;
			toc2string($val, $level + 1, $dir . '/' . $key);
		}
		else {
			$label = labelify($val);
			$str .= str_repeat("\t", $level) . '* ' . '[[' . $cfg['content_dir'] . $dir . '/' . str_replace($cfg['suffix'], '', $val) . '|' . $label . ']]' . PHP_EOL;
		}
	}
}

////////////////////////////////////////////////////////////////

$str = '';
$dir = '';

$toc = toc_from_dirs($cfg['content_dir']);
toc2string($toc['content'], 0, $dir);
file_put_contents($cfg['toc_file'], $str);

header('Location: ' . 'index.php');
exit();

