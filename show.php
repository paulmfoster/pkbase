<?php

include 'init.php';

$page = $_GET['page'];
$title = $_GET['title'];

$text = file_get_contents($page . '.md');

$content = $pd->text($text);

include 'views/head.view.php';
echo $content;
include 'views/footer.view.php';

