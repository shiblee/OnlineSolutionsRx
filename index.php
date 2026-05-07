<?php

// error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

ini_set('display_errors', '1');


// Version
define('VERSION', '3.0.3.8');

// Configuration
if (is_file(__DIR__ . '/config.php')) {
	require_once(__DIR__ . '/config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

start('catalog');