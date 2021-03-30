<?php
include 'init.php';
$page = fork('page', 'P', 'index.php');
$pkb->delete_node($page);
redirect('index.php');
