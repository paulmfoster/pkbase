<?php

include 'init.php';

$page = $_GET['page'] ?? FALSE;
$title = $_GET['title'] ?? FALSE;

if (!$page && !$title) {

	// shouldn't happen
	if (count($_POST) == 0) {
		emsg('F', 'Unexplainable error');
		header('Location: ' . $base_url . 'index.php');
		exit();
	}

	// POST response
	$pkb2->update_node($_POST);

	header('Location: ' . $base_url . 'index.php');
	exit();
}
else {

	$content = $pkb2->get_file($page . $cfg['suffix']);
	$fields = [
		'newtitle' => [
			'name' => 'newtitle',
			'type' => 'text',
			'size' => 50,
			'maxlength' => 50
		],
		'title' => [
			'name' => 'title',
			'type' => 'hidden',
			'value' => $title
		],
		'page' => [
			'name' => 'page',
			'type' => 'hidden',
			'value' => $page
		],
		'content' => [
			'name' => 'content',
			'type' => 'textarea',
			'rows' => 50,
			'cols' => 75
		],
		's1' => [
			'name' => 's1',
			'type' => 'submit',
			'value' => 'Update'
		]
	];

	$form = new form($fields);

	$buttons = 'RI';
}

$view_file = 'views/nodeedt.view.php';
include 'view.php';
