<?php

include 'init.php';

$search_for = $_POST['search_query'] ?? NULL;

if (is_null($search_for)) {
	emsg('F', 'Empty search query');
	header('Location: ' . 'index.php');
	exit();
}

$results = $pkb->get_search_results($search_for);
$numfiles = count($results);

$page = '';
$title = 'Search Results';

include 'views/head.view.php';
include 'views/search.view.php';
include 'views/footer.view.php';
