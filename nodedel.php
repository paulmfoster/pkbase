<?php

include 'init.php';

/*
$action = $_POST['s1'] ?? 'new';

if ($action == 'new') {
	$page_options = [];

	foreach ($pkb2->recs as $page) {
		if (!is_dir($page['name'])) {
			$posn = strrpos($page['parent'], '/');
			if ($posn !== FALSE) {
				// select the last segment of parent name
				$stub_parent = substr($page['parent'], $posn);
			}
			else {
				$stub_parent = $page['parent'];
			}

			$page_options[] = ['lbl' => '(.../' . $stub_parent . ') ' . $page['title'], 'val' => $page['name']];
		}
	}

	$fields = [
		'page' => [
			'name' => 'page',
			'type' => 'select',
			'options' => $page_options
		],
		's1' => [
			'name' => 's1',
			'type' => 'submit',
			'value' => 'Delete'
		]
	];

	$form = new form($fields);

}
else {

	$pkb2->delete_node($_POST);
	header('Location: ' . 'index.php');
	exit();
}

 */

$page = $_GET['page'] ?? FALSE;
$title = $_GET['title'] ?? FALSE;

$action = $_POST['s1'] ?? 'new';

if ($action == 'new' && $page && $title) {

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

	$form = new form($fields);

	$content = $pkb2->get_file($page . $cfg['suffix']);

	$buttons = 'RI';

}
elseif ($action == 'Confirm Deletion') {
	$pkb2->delete_node($_POST['page']);

	header('Location: ' . 'index.php');
	exit();
}
else {
	// shouldn't happen
	header('Location: ' . 'index.php');
	exit();
}

$view_file = 'views/nodedel.view.php';

include 'view.php';

