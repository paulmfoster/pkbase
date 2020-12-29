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
		// confirmed deletion
		$ekb3->delete_node($_POST['page']);
		header('Location: ' . 'index.php');
		exit();
	}
}
else {
	// show the page and confirm button
	$title = $pkb3->get_title_from_filename(pathinfo($page, PATHINFO_FILENAME));
	$sidebar = $pkb3->parent_sidebar($page);
	$buttons = 'I';

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
	$content = $pkb3->get_content($page);
}

$view_file = 'views/nodedel.view.php';

include 'view.php';

