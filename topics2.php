<?php
include 'init.php';

$add = $_POST['s1'] ?? FALSE;
$delete = $_POST['s2'] ?? FALSE;

if (!$add && !$delete) {
	redirect('index.php');
}
	
if ($add) {
	$pkb->add_topic($_POST['parent'], $_POST['newtopic']);
}
elseif ($delete) {
	$pkb->delete_topic($_POST['delete']);
}
redirect('index.php');
