<?php

$cfgfile = CFGDIR . 'config.ini';
if (!file_exists($cfgfile)) {
    die('File <strong>' . CFGDIR . 'config.ini</strong> is missing. Aborting.');
}
$cfg = parse_ini_file(CFGDIR . 'config.ini');

// 2592000 = 30 days
ini_set('session.gc_maxlifetime', 2592000);
ini_set('session.cookie_lifetime', 2592000);
session_set_cookie_params(2592000);
session_start();

load('errors');
load('messages');
$form = load('form');
$pkb = model('pkbase');

