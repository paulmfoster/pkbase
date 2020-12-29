<?php
include 'init.php';

$which_tree = $_GET['tree'] ?? NULL;

if (is_null($which_tree)) {
	$sidebar = $pkb3->get_sidebar($cfg['content_dir']);
}
else {
	$sidebar = $pkb3->get_sidebar($which_tree);
}

$buttons = 'TA';
$page = '';
$title = 'Welcome';

include $common_dir . 'Parsedown.php';
$pd = new Parsedown;
$readme = file_get_contents('README.md');
$content = $pd->text($readme);

$view_file = 'views/index.view.php';
include 'view.php';
