<?php

include 'init.php';

$search_for = $_POST['search_query'] ?? NULL;

if (is_null($search_for)) {
	emsg('F', 'Empty search query');
	header('Location: ' . 'index.php');
	exit();
}

$results = $pkb2->get_search_results($search_for);
$numfiles = count($results);

$buttons = 'RIA';
$page = '';
$title = 'Search Results';

$view_file = 'views/search.view.php';

include 'view.php';

