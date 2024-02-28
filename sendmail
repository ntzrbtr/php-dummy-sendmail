<?php
// Disable time limit.
set_time_limit(0);

// Define the base path of the application.
define('APP_BASE_PATH', Phar::running() ? Phar::running() : dirname(__FILE__));

// Include Composer's autoloader.
$loader = require_once APP_BASE_PATH . '/vendor/autoload.php';

// Use and start the application.
$app = new \PDS\PDSApplication('zbateson/php-dummy-sendmail', '@VERSION@');
$app->run();
