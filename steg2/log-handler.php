<?php

// Require Composer's autoloader to load Monolog classes
require 'monolog/vendor/autoload.php';

// Import the Logger class from Monolog namespace
use Monolog\Logger;
// Import the StreamHandler class from Monolog namespace
use Monolog\Handler\StreamHandler;

// Create a new Logger instance with a channel name
$log = new Logger('my_logger');

// Add a handler to the logger that writes log messages to a file
$log->pushHandler(new StreamHandler('app.log', Logger::INFO));

// Log a test message
$log->info('This is a test message from Monolog!');

echo 'Test message logged successfully.';
