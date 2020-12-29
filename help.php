<?php

include 'init.php';

$page = '';
$title = 'Help';
$buttons = 'AT';
$sidebar = $pkb3->get_sidebar($cfg['content_dir']);

$view_file = 'views/help.view.php';
include 'view.php';

