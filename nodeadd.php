<?php

include 'init.php';

$action = $_POST['s1'] ?? 'new';

$sidebar = $pkb3->get_sidebar($cfg['content_dir']);


if ($action == 'new') {

	$extensions = explode(';', $cfg['extensions']);
	$ext_options = [];
	foreach ($extensions as $ext) {
		$ext_options[] = ['lbl' => $ext, 'val' => $ext];
	}

	$dirs = $pkb3->get_dirs();
	$dirs_options = [];
	foreach ($dirs as $dir) {
		$dirs_options[] = ['lbl' => $dir, 'val' => $dir];
	}

	$fields = [
		'parent' => [
			'name' => 'parent',
			'type' => 'select',
			'options' => $dirs_options
		],
		'title' => [
			'name' => 'title',
			'type' => 'text',
			'size' => 50,
			'maxlength' => 50
		],
		'content' => [
			'name' => 'content',
			'type' => 'textarea',
			'rows' => 50,
			'cols' => 75
		],
		'extension' => [
			'name' => 'extension',
			'type' => 'select',
			'options' => $ext_options
		],
		's1' => [
			'name' => 's1',
			'type' => 'submit',
			'value' => 'Save'
		]
	];

	$form = new form($fields);

}
elseif ($action == 'Save') {
	$filename = $pkb3->add_node($_POST);
	emsg('S', 'Node successfully added');
	header('Location: ' . 'show.php?page=' . $filename);
	exit();
}
	
$page = '';
$title = 'Add Node';
$buttons = 'R';

$view_file = 'views/nodeadd.view.php';
include 'view.php';



