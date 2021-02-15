<?php

include 'init.php';

$page = $_GET['page'] ?? NULL;
if (is_null($page)) {
	header('Location: index.php');
	exit();
}

$title = $pkb->get_title($page);

$sidebar = $pkb->get_sidebar($page);
$buttons = 'TEDA';

$content = $pkb->get_content($page);

$view_file = $cfg['viewdir'] . 'show.view.php';
include 'view.php';

