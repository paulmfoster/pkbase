<?php

include 'init.php';

$page = $_GET['page'];
$title = $_GET['title'];
if ($is_author) {
	$buttons = 'RITEDA';
}
else {
	$buttons = 'R';
}

$text = file_get_contents($page . '.md');

$content = $pd->text($text);

$view_file = 'views/show.view.php';
include 'view.php';

