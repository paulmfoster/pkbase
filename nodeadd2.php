<?php
include 'init.php';
$filename = $pkb->add_node($_POST);
emsg('S', 'Node successfully added');
redirect('show.php?page=' . $filename);
