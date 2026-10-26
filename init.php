<?php

// define system directories
define('SYSDIR', 'system/');
define('INCDIR', SYSDIR . 'includes/');
define('LIBDIR', SYSDIR . 'libraries/');

// define application directories
define('APPDIR', 'app/');
define('CFGDIR', APPDIR . 'config/');
define('PRINTDIR', APPDIR . 'printq/');
define('IMGDIR', APPDIR . 'images/');
define('DATADIR', APPDIR . 'data/');
define('MODELDIR', APPDIR . 'models/');
define('VIEWDIR', APPDIR . 'views/');

// read config file
$cfgfile = CFGDIR . 'config.ini';
if (!file_exists($cfgfile)) {
    die('File <strong>' . CFGDIR . 'config.ini</strong> is missing. Aborting.');
}
$cfg = parse_ini_file(CFGDIR . 'config.ini');

// NOTE: 2nd argument of symlink() can't end in slash!

// set up content_dir
$contentdir = 'content';
if (!is_link($contentdir)) {
    if (isset($cfg['content_dir'])) {
        if (!symlink($cfg['content_dir'], $contentdir)) {
            die("<h1>Fail on symlinking content_dir.</h1>");
        }
    }
    else {
        die("<h1><code>content_dir<code> not set in config file.</h1>");
    }
}
define('CONTENTDIR', 'content/');

// set up includes directory
$incdir = SYSDIR . 'includes';
if (!is_link($incdir)) {
    if (isset($cfg['extincdir'])) {
        if (!symlink($cfg['extincdir'], $incdir)) {
            dir("<h1>Fail on symlinking includes dir.</h1>");
        }
    }
    else {
        die("<h1><code>extincdir<code> not set in config file.</h1>");
    }
}
define('INCDIR', SYSDIR . 'includes/');

// set up library directory
$libdir = SYSDIR . 'libraries';
if (!is_link($libdir)) {
    if (isset($cfg['extlibdir'])) {
        if (!symlink($cfg['extlibdir'], $libdir)) {
            die("<h1>Fail on symlinking libraries dir.</h1>");
        }
    }
    else {
        die("<h1><code>extlibdir<code> not set in config file.</h1>");
    }
}
define('LIBDIR', SYSDIR . 'libraries/');


// provide common utilities
include INCDIR . 'utils.inc.php';

// 2592000 = 30 days
ini_set('session.gc_maxlifetime', 2592000);
ini_set('session.cookie_lifetime', 2592000);
session_set_cookie_params(2592000);
session_start();

load('errors');
load('messages');
$pd = load('Parsedown');
$pkb = model('pkbase');

