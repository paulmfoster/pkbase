<?php

function instrument($label, $var)
{
	echo $label;
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

include 'includes/errors.inc.php';
include 'includes/messages.inc.php';
include 'models/pkbase.mdl.php';
include 'libraries/Parsedown.php';

$cfg = parse_ini_file('config/config.ini');

$pkb = new pkbase();
$pd = new Parsedown();


