<?php

include 'init.php';

$tree = $_GET['tree'] ?? NULL;

if (is_null($tree)) {
    $sidebar = $pkb->get_sidebar($cfg['content_dir']);
}
else {
    $sidebar = $pkb->get_sidebar($pkb->unhide($tree));
}

$readme = file_get_contents('README.md');
$content = $pd->text($readme);

$title = 'Welcome';
$page = '';
$sidebar = $sidebar;

include VIEWDIR . 'index.view.php';

