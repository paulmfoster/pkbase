<?php

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

include $cfg['incdir'] . 'errors.inc.php';
include $cfg['incdir'] . 'messages.inc.php';
include $cfg['libdir'] . 'form.lib.php';
include $cfg['modeldir'] . 'pkb4.mdl.php';

include 'buttons.php';

$pkb = new pkb4();

