<?php
include 'init.php';

$file = $pkb2->random_file();
if ($file == 1) {
	$page = '';
	if ($is_author) {
		$title = 'No pages. Please create one.';
		$buttons = 'IA';
		$content = <<<EOT1
<p>You haven't created any pages yet, or if you have, they aren't
in the index. If you haven't created any pages yet, do that
now. If you have created pages, but the index doesn't show them,
then reindex now using the "Rebuild Index" button.</p>
EOT1;
	}
	else {
		$title = 'No pages. Ask one to be created.';
		$buttons = '';
		$content = <<<EOT2
<p>No pages have been created yet. You'll need to wait until content
has been created for this site.</p>
EOT2;
	}
}
elseif ($file == 2) {
	$page = $file['name'];
	$title = 'Failure To Find Article';
	if ($is_author) {
		$buttons = 'I';
		$content = <<<EOT
<p>Apparently something has happened to your content directory. 
After a reasonable number of tries, we're unable to find the files
from your index in the content directory. The answer here is to
reindex your directory using the "Rebuild Index" button on the 
home page. Please do that now to avoid future difficulties.</p>
EOT;
	}
	else {
		$buttons = '';
		$content = <<<EOT3
<p>Apparently something has happened to your content directory. 
After a reasonable number of tries, we're unable to find the files
from your index in the content directory. The answer here is to
reindex the directory using the "Rebuild Index" button on the 
home page. Unfortunately, you can't do that. Instead, ask the
author to reindex for you..</p>
EOT3;
	}
}
else {
	$page = $file['name'];
	$title = $file['title'];

	if ($is_author) {
		$buttons = 'RITEDA';
	}
	else {
		$buttons = 'R';
	}

	$text = file_get_contents($page . $cfg['suffix']);
	if ($cfg['suffix'] == '.md') {
		// if it's markdown...
		$content = $pd->text($text);
	}
	else {
		// otherwise...
		$content = $text;
	}
}

$view_file = 'views/show.view.php';
include 'view.php';
