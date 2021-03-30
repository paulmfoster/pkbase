<?php

function instrument($label, $var)
{
	echo $label;
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function redirect($url)
{
	header("Location: $url");
	exit();
}

function model($name)
{
	global $cfg, $db;

	$filename = $cfg['modeldir'] . $name . '.mdl.php';
	if (!file_exists($filename)) {
		die("Model $name doesn't exist!");
	}
	require_once($filename);
	$obj = new $name($db);
	return $obj;
}

function library($name)
{
	global $cfg;

	$filename = $cfg['libdir'] . $name . '.lib.php';
	if (!file_exists($filename)) {
		die("Library $name doesn't exist!");
	}
	require_once($filename);
	$obj = new $name();
	return $obj;
}


function view($page_title, $data, $return, $view_file, $focus_field = '')
{
	global $cfg, $nav, $form;

	extract($data);
	include $cfg['viewdir'] . 'head.view.php';
	include $cfg['viewdir'] . $view_file . '.view.php';
	include $cfg['viewdir'] . 'footer.view.php';
}

function fork($varname, $method, $failurl)
{
	if ($method == 'P') {
		$var = $_POST[$varname] ?? NULL;
	}
	elseif ($method == 'G') {
		$var = $_GET[$varname] ?? NULL;
	}
	if (is_null($var)) {
		header('Location: ' . $failurl);
		exit;
	}
	return $var;
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
$form = new form;
include $cfg['modeldir'] . 'pkb4.mdl.php';

include 'buttons.php';

$pkb = new pkb4();

