<?php

include 'init.php';

$page = $_GET['page'] ?? NULL;
if (is_null($page)) {
    redirect('index.php');
}

$upage = $pkb->unhide($page);

$sidebar = $pkb->get_sidebar($upage);
$title = $pkb->get_title($upage);
$page = $upage;
$content = $pkb->get_content($upage);

include VIEWDIR . 'show.view.php';

