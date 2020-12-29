<?php

function get_or_post($parm)
{
	if (isset($_GET[$parm])) {
		$method = 'G';
		$retval = $_GET[$parm];
	}
	elseif (isset($_POST[$parm])) {
		$method = 'P';
		$retval = $_POST[$parm];
	}
	else {
		$method = 'X';
		$retval = NULL;
	}

	return [$method, $retval];
}

function instrument($label, $var)
{
	echo $label;
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

$cfg = parse_ini_file('config/config.ini');

// 2592000 = 30 days
ini_set('session.gc_maxlifetime', 2592000);
ini_set('session.cookie_lifetime', 2592000);
session_set_cookie_params(2592000);
session_name($cfg['session_name']);
session_start();

$protocol = 'http://';
$http_host = $_SERVER['HTTP_HOST'];

$base_dir = dirname(realpath(__FILE__)) . DIRECTORY_SEPARATOR;
$base_dir_len = strlen($base_dir);
$doc_root = $_SERVER['DOCUMENT_ROOT'];
$doc_root_len = strlen($doc_root);

if ($base_dir_len == $doc_root_len) {
	$app_subdir = '';
}
else {
	$app_subdir = substr($base_dir, strlen($_SERVER['DOCUMENT_ROOT']) + 1);
}
$base_url = sprintf("%s%s/%s", $protocol, $http_host, $app_subdir);

$common_dir = 'common/';

include $common_dir . 'errors.inc.php';
include $common_dir . 'messages.inc.php';
include $common_dir . 'form.lib.php';
include $common_dir . 'pkb3.mdl.php';
// include $common_dir . 'Parsedown.php';
// include $common_dir . 'database.lib.php';

include 'buttons.php';

// $db = new database($cfg);

$pkb3 = new pkb3();
// $pd = new Parsedown();

