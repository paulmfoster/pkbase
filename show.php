<?php

include 'init.php';

$page = $_GET['page'] ?? NULL;
if (is_null($page)) {
    redirect('index.php');
}

// $upage = $pkb->unhide($page);

$sidebar = $pkb->get_sidebar($page);
$title = $pkb->get_title($page);
$content = $pkb->get_content($page);

include VIEWDIR . 'show.view.php';

