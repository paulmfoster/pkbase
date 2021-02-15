<?php

function show_buttons($buttons, $page = '', $title = '')
{
	echo '<div id="locator-buttons">' . PHP_EOL;

	form::button('Help', 'index.php');

	if (strchr($buttons, 'T')) {
		form::button('Topics', 'topics.php');
	}
	if (strchr($buttons, 'E')) {
		form::button('Edit Page', 'nodeedt.php?page=' . $page);
	}
	if (strchr($buttons, 'D')) {
		form::button('Delete Page', 'nodedel.php?page=' . $page);
	}
	if (strchr($buttons, 'A')) {
		form::button('Add Page', 'nodeadd.php');
	}

	echo '</div> <!-- locator-buttons -->' . PHP_EOL;
}
