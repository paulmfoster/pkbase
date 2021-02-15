<?php

include 'init.php';

$add = $_POST['s1'] ?? FALSE;
$delete = $_POST['s2'] ?? FALSE;

if (!$add && !$delete) {
	
	$dirs = $pkb->get_dirs();
	$dirs_options = [];
	foreach ($dirs as $dir) {
		$dirs_options[] = ['lbl' => $dir, 'val' => $dir];
	}
	
	$afields = [
		'parent' => [
			'name' => 'parent',
			'type' => 'select',
			'label' => 'Parent Directory',
			'options' => $dirs_options
		],
		'newtopic' => [
			'name' => 'newtopic',
			'type' => 'text',
			'label' => 'New Topic',
			'size' => 50,
			'maxlength' => 50
		],
		's1' => [
			'name' => 's1',
			'type' => 'submit',
			'value' => 'Add Topic'
		]
	];

	$aform = new form($afields);

	$dfields = [
		'delete' => [
			'name' => 'delete',
			'type' => 'select',
			'label' => 'Directory/Topic',
			'options' => $dirs_options
		],
		's2' => [
			'name' => 's2',
			'type' => 'submit',
			'value' => 'Delete Topic'
		]
	];

	$dform = new form($dfields);
}
elseif ($add) {
	$pkb->add_topic($_POST['parent'], $_POST['newtopic']);
	header('Location: ' . 'index.php');
	exit();

}
elseif ($delete) {
	$pkb->delete_topic($_POST['delete']);
	header('Location: ' . 'index.php');
	exit();
}

$sidebar = $pkb->get_sidebar($cfg['content_dir']);
$buttons = '';
$page = '';
$title = 'Topics';

$view_file = $cfg['viewdir'] . 'topics.view.php';
include 'view.php';

