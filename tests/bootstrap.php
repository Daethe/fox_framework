<?php

defined('APP_DEV') or define('APP_DEV', true);
defined('ROOT') or define('ROOT', __DIR__);

error_reporting(E_ALL);
if (function_exists('date_default_timezone_set') && function_exists('date_default_timezone_get')) {
	date_default_timezone_set(@date_default_timezone_get());
}
require_once __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Fixtures/SomeController.php';