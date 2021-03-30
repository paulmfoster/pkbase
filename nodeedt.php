<?php
include 'init.php';
$page = fork('page', 'G', 'index.php');

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

$form->set($fields);

$d = [
	'sidebar' => $sidebar,
	'buttons' => 'I',
	'page' => $page,
	'title' => $title
];
view('Edit Page', $d, 'nodeedt2.php', 'nodeedt');

