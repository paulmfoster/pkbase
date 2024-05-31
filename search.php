<?php

include 'init.php';

$search_for = fork('search_query', 'P', 'index.php');

$results = $pkb->get_search_results($search_for);

$page = '';
$title = 'Search Results';
$sidebar = $pkb->get_sidebar($cfg['content_dir']);

include VIEWDIR . 'search.view.php';

