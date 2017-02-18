<?php

// Remove next line for production
defined('APP_DEV') or define('APP_DEV', true);

defined('ROOT') or define('ROOT', __DIR__);

require 'Core/App.php';
\Core\App::load();