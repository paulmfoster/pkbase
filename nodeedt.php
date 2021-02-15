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
		$pkb->update_node($_POST);
		header('Location: ' . 'index.php');
		exit();
	}
}
else {
	// show the page and confirm button
	$sidebar = $pkb->get_sidebar($page);
	$title = $pkb->get_title($page);
	$buttons = 'I';

	$extensions = explode(';', $cfg['extensions']);
	$ext_options = [];
	foreach ($extensions as $ext) {
		$ext_options[] = ['lbl' => $ext, 'val' => $ext];
	}

	$extension = pathinfo($page, PATHINFO_EXTENSION);
	$content = file_get_contents($page);

	$fields = [
		'page' => [
			'name' => 'page',
			'type' => 'hidden',
			'value' => $page
		],
		'newtitle' => [
			'name' => 'newtitle',
			'type' => 'text',
			'label' => 'New Title',
			'value' => $title,
			'size' => 50,
			'maxlength' => 50
		],
		'extension' => [
			'name' => 'extension',
			'type' => 'select',
			'label' => 'Extension',
			'value' => $extension,
			'options' => $ext_options
		],
		'content' => [
			'name' => 'content',
			'type' => 'textarea',
			'rows' => 50,
			'cols' => 75,
			'label' => 'Content',
			'value' => $content
		],		
		's1' => [
			'name' => 's1',
			'type' => 'submit',
			'value' => 'Save Edits'
		]
	];

	$form = new form($fields);
}

$view_file = $cfg['viewdir'] . 'nodeedt.view.php';
include 'view.php';

