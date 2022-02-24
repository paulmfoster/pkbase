<?php

$cfgfile = 'config/config.ini';
if (!file_exists($cfgfile)) {
	copy('config/config.sample', 'config/config.ini');
}
$cfg = parse_ini_file('config/config.ini');

include 'grotto_check.php';
include $cfg['grottodir'] . 'misc.inc.php';

// 2592000 = 30 days
ini_set('session.gc_maxlifetime', 2592000);
ini_set('session.cookie_lifetime', 2592000);
session_set_cookie_params(2592000);
session_name($cfg['session_name']);
session_start();

grotto('errors');
grotto('messages');
$form = grotto('form');

include $cfg['modeldir'] . 'pkb4.mdl.php';
$pkb = new pkb4();

