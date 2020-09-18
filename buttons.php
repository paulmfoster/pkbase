<?php

function show_buttons($buttons, $page = '', $title = '')
{
	echo '<div id="locator-buttons">' . PHP_EOL;

	form::button('Help', 'help.php');

	if (strchr($buttons, 'R')) {
		form::button('Random Page', 'index.php');
	}
	if (strchr($buttons, 'I')) {
		form::button('Rebuild Index', 'rebuild.php');
	}
	if (strchr($buttons, 'E')) {
		form::button('Edit Page', 'nodeedt.php?page=' . $page . '&title=' . $title);
	}
	if (strchr($buttons, 'D')) {
		form::button('Delete Page', 'nodedel.php?page=' . $page . '&title=' . $title);
	}
	if (strchr($buttons, 'A')) {
		form::button('Add Page', 'nodeadd.php');
	}

	echo '</div> <!-- locator-buttons -->' . PHP_EOL;
}
