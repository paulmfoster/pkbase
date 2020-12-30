<?php

include 'init.php';

$search_for = $_POST['search_query'] ?? NULL;

if (is_null($search_for)) {
	emsg('F', 'Empty search query');
	header('Location: ' . 'index.php');
	exit();
}

$sidebar = $pkb3->get_sidebar($cfg['content_dir']);
$results = $pkb3->get_search_results($search_for);

$buttons = 'A';
$page = '';
$title = 'Search Results';

$view_file = 'views/search.view.php';

include 'view.php';

