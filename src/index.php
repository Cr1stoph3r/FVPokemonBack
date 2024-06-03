<?php

require __DIR__ . '/../vendor/autoload.php';
// Load environment variables from the .env file in the project root
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Load database configuration
require __DIR__ . '/../config/database.php';

require __DIR__ . '/routes/web.php';
