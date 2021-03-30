<?php
include 'init.php';

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
	],
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

$form->set($fields);

$d = [
	'sidebar' => $pkb->get_sidebar($cfg['content_dir']),
	'buttons' => '',
	'page' => '',
	'title' => 'Topics'
];
view('Topics', $d, 'topics2.php', 'topics');

