<?php

include 'init.php';

$page = $_GET['page'];

$title = $pkb3->get_title_from_filename(pathinfo($page, PATHINFO_FILENAME));

$sidebar = $pkb3->parent_sidebar($page);
$buttons = 'TEDA';

$content = $pkb3->get_content($page);
// $content = file_get_contents($page);

// $content = $pd->text($text);

$view_file = 'views/show.view.php';
include 'view.php';

