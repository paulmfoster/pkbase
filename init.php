<?php

function instrument($label, $var)
{
	echo $label;
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

$common_dir = 'common/';
include $common_dir . 'errors.inc.php';
include $common_dir . 'messages.inc.php';
include $common_dir . 'pkbase.mdl.php';
include $common_dir . 'Parsedown.php';

$cfg = parse_ini_file('config/config.ini');

$pkb = new pkbase();
$pd = new Parsedown();

