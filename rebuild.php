<?php

include 'init.php';

$action = $_POST['s1'] ?? 'virgin';

if ($action == 'virgin') {
	$fields = [
		's1' => [
			'name' => 's1',
			'type' => 'submit',
			'value' => 'Rebuild'
		]
	];

	$form = new form($fields);

	$page = '';
	$title = 'Rebuild';
	$view_file = 'views/rebuild.view.php';
	$buttons = '';
	include 'view.php';
}
elseif ($action == 'Rebuild') {
	$pkb2->rebuild();
	emsg('S', 'System successfully rebuilt');
	header('Location: ' . 'index.php');
	exit();
}

