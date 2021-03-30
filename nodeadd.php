<?php
include 'init.php';

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

$form->set($fields);
	
$d = [
	'sidebar' => $pkb->get_sidebar($cfg['content_dir']),
	'buttons' => 'R',
	'page' => '',
	'title' => 'Add Node'
];
view($d['title'], $d, 'nodeadd2.php', 'nodeadd');

