<?php

include 'init.php';

$action = $_POST['s1'] ?? 'new';

$sidebar = $pkb->get_sidebar($cfg['content_dir']);


if ($action == 'new') {

	$extensions = explode(';', $cfg['extensions']);
	$ext_options = [];
	foreach ($extensions as $ext) {
		$ext_options[] = ['lbl' => $ext, 'val' => $ext];
	}

	$dirs = $pkb->get_dirs();
	$dirs_options = [];
	foreach ($dirs as $dir) {
		$dirs_options[] = ['lbl' => $dir, 'val' => $dir];
	}

	$fields = [
		'parent' => [
			'name' => 'parent',
			'type' => 'select',
			'label' => 'Parent Directory',
			'options' => $dirs_options
		],
		'title' => [
			'name' => 'title',
			'type' => 'text',
			'size' => 50,
			'label' => 'Title',
			'maxlength' => 50
		],
		'extension' => [
			'name' => 'extension',
			'type' => 'select',
			'label' => 'Extension',
			'options' => $ext_options
		],
		'content' => [
			'name' => 'content',
			'type' => 'textarea',
			'rows' => 25,
			'cols' => 75,
			'label' => 'Content'
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
	$filename = $pkb->add_node($_POST);
	emsg('S', 'Node successfully added');
	header('Location: ' . 'show.php?page=' . $filename);
	exit();
}
	
$page = '';
$title = 'Add Node';
$buttons = 'R';

$view_file = $cfg['viewdir'] . 'nodeadd.view.php';
include 'view.php';



