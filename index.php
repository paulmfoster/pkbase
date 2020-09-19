<?php
include 'init.php';

$file = $pkb2->random_file();
if ($file == 1) {
	$page = '';
	$title = 'No pages. Please create one.';
	$buttons = 'IA';
	$content = <<<EOT1
<p>You haven't created any pages yet, or if you have, they aren't
in the index. If you haven't created any pages yet, do that
now. If you have created pages, but the index doesn't show them,
then reindex now using the "Rebuild Index" button.</p>
EOT1;
}
elseif ($file == 2) {
	$page = $file['name'];
	$title = 'Failure To Find Article';
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
	$page = $file['name'];
	$title = $file['title'];
	$buttons = 'RITEDA';

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
