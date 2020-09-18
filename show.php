<?php

include 'init.php';

$page = $_GET['page'];
$title = $_GET['title'];
$buttons = 'RIEDA';

$text = file_get_contents($page . '.md');

$content = $pd->text($text);

$view_file = 'views/show.view.php';
include 'view.php';

