<?php
include 'init.php';
$page = fork('page', 'G', 'index.php');

$fields = [
	'page' => [
		'name' => 'page',
		'type' => 'hidden',
		'value' => $page
	],
	's1' => [
		'name' => 's1',
		'type' => 'submit',
		'value' => 'Confirm Deletion'
	]
];
$form->set($fields);
$d = [
	'sidebar' => $pkb->get_sidebar($page),
	'buttons' => 'I',
	'page' => '',
	'title' => $pkb->get_title($page),
	'content' => $pkb->get_content($page)
];
view('Delete Node', $d, 'nodedel2.php', 'nodedel');
