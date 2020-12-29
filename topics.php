<?php

include 'init.php';

$add = $_POST['s1'] ?? FALSE;
$delete = $_POST['s2'] ?? FALSE;

if (!$add && !$delete) {
	
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
		'delete' => [
			'name' => 'delete',
			'type' => 'select',
			'options' => $dirs_options
		],
		's1' => [
			'name' => 's1',
			'type' => 'submit',
			'value' => 'Add Topic'
		],
		's2' => [
			'name' => 's2',
			'type' => 'submit',
			'value' => 'Delete Topic'
		],
		'newtopic' => [
			'name' => 'newtopic',
			'type' => 'text',
			'size' => 50,
			'maxlength' => 50
		]
	];

	$form = new form($fields);
}
elseif ($add) {
	$pkb3->add_topic($_POST['parent'], $_POST['newtopic']);
	header('Location: ' . 'index.php');
	exit();

}
elseif ($delete) {
	$pkb3->delete_topic($_POST['delete']);
	header('Location: ' . 'index.php');
	exit();
}

$sidebar = $pkb3->get_sidebar($cfg['content_dir']);
$buttons = '';
$page = '';
$title = 'Topics';

$view_file = 'views/topics.view.php';
include 'view.php';

