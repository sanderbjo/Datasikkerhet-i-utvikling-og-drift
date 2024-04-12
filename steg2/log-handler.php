<?php

// Require Composer's autoloader to load Monolog classes
require 'monolog/vendor/autoload.php';

// Import the Logger class from Monolog namespace
use Monolog\Logger;
// Import the StreamHandler class from Monolog namespace
use Monolog\Handler\StreamHandler;

// Create a new Logger instance with a channel name
$log = new Logger('my_logger');

// Create a formatter with a custom log format
$formatter = new LineFormatter("[%datetime%] %extra.ip% %level_name%: %message% %context%\n", "Y-m-d H:i:s");

// Add a handler for access logs (INFO level only) with the custom formatter
$accessHandler = new StreamHandler('access.log', Logger::INFO);
$accessHandler->setFormatter($formatter);
$log->pushHandler($accessHandler);

// Log a test message
$log->info('This is a test message from Monolog!');

echo 'Test message logged successfully.';
