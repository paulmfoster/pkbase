<?php

$cfgfile = 'config/config.ini';
if (!file_exists($cfgfile)) {
	copy('config/config.sample', 'config/config.ini');
}
$cfg = parse_ini_file('config/config.ini');

/* =========== GROTTO CODE CHECK ============= */

if (!file_exists($cfg['incdir']) || !file_exists($cfg['libdir'])) {
	$message = <<< EOT

This software relies on another package called "grotto", and I can't find
it on your system. It should be available from where you got this software.
Download it from there and install it, ideally located outside the tree for
this software. Optionally, you may locate it within the tree for this
software. In your main software, you should have a file called
<code>config/config.ini</code>.  Look for the following two lines in it:

incdir = "../grotto/"
libdir = "../grotto/"

Edit those lines to point to the the location where you downloaded the
"grotto" package.

EOT;
	
	die(nl2br($message));
}

/* ========== END GROTTO CODE CHECK =========== */

include $cfg['incdir'] . 'misc.inc.php';

// 2592000 = 30 days
ini_set('session.gc_maxlifetime', 2592000);
ini_set('session.cookie_lifetime', 2592000);
session_set_cookie_params(2592000);
session_name($cfg['session_name']);
session_start();

include $cfg['incdir'] . 'errors.inc.php';
include $cfg['incdir'] . 'messages.inc.php';

include $cfg['libdir'] . 'form.lib.php';
$form = new form;
include $cfg['modeldir'] . 'pkb4.mdl.php';

$pkb = new pkb4();

