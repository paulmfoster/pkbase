<?php

include 'init.php';
$page = $_GET['page'] ?? FALSE;
$action = $_POST['s1'] ?? FALSE;

if (!$page) {
	if (!$action) {
		// random call to this script
		header('Location: index.php');
		exit();
	}
	else {
		// confirmed edit
		$pkb3->update_node($_POST);
		header('Location: ' . 'index.php');
		exit();
	}
}
else {
	// show the page and confirm button
	$title = $pkb3->get_title_from_filename(pathinfo($page, PATHINFO_FILENAME));
	$sidebar = $pkb3->parent_sidebar($page);
	$buttons = 'I';

	$extensions = explode(';', $cfg['extensions']);
	$ext_options = [];
	foreach ($extensions as $ext) {
		$ext_options[] = ['lbl' => $ext, 'val' => $ext];
	}

	$extension = pathinfo($page, PATHINFO_EXTENSION);

	$fields = [
		'page' => [
			'name' => 'page',
			'type' => 'hidden',
			'value' => $page
		],
		'newtitle' => [
			'name' => 'newtitle',
			'type' => 'text',
			'size' => 50,
			'maxlength' => 50
		],
		'extension' => [
			'name' => 'extension',
			'type' => 'select',
			'options' => $ext_options
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
			'value' => 'Save Edits'
		]
	];

	$form = new form($fields);
	$content = file_get_contents($page);
}

$view_file = 'views/nodeedt.view.php';
include 'view.php';

