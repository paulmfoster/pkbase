<?php
include 'init.php';

$file = $pkb->random_file();
if ($file === FALSE) {
	$title = 'Failure To Find Article';
	$content = <<<EOT
Apparently something has happened to your content directory. 
After a reasonable number of tries, we're unable to find the files
from your index in the content directory. The answer here is to
reindex your directory using the "Rebuild Index" button on the 
home page. Please do that now to avoid future difficulties.
EOT;
}
else {
	$page = $file[0];
	$title = $file[1];

	$text = file_get_contents($page . $cfg['suffix']);

	$content = $pd->text($text);
}

include 'views/head.view.php';
echo $content;
include 'views/footer.view.php';
