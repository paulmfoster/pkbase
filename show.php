<?php

include 'init.php';
$page = fork('page', 'G', 'index.php');
$sidebar = $pkb->get_sidebar($page);
$title = $pkb->get_title($page);
$content = $pkb->get_content($page);
$d = [
	'page' => $page,
	'sidebar' => $sidebar,
	'title' => $title,
	'content' => $content
];
view($title, $d, '', 'show');

