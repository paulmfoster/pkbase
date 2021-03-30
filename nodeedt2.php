<?php
include 'init.php';
$page = fork('page', 'P', 'index.php');
$pkb->update_node($_POST);
redirect('index.php');
