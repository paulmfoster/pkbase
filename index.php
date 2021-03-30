<?php
include 'init.php';

$which_tree = $_GET['tree'] ?? NULL;

if (is_null($which_tree)) {
	$sidebar = $pkb->get_sidebar($cfg['content_dir']);
}
else {
	$sidebar = $pkb->get_sidebar($which_tree);
}

$buttons = 'TA';
$page = '';
$title = 'Welcome';

include $cfg['libdir'] . 'Parsedown.php';
$pd = new Parsedown;
$readme = file_get_contents('README.md');
$content = $pd->text($readme);

$d = [
	'sidebar' => $sidebar,
	'buttons' => 'TA',
	'page' => '',
	'title' => 'Welcome',
	'content' => $content
];
view('Welcome', $d, '', 'index');
