<?php

use Slim\Factory\AppFactory;
use Nyholm\Psr7\Factory\Psr17Factory;
use Slim\Factory\ServerRequestCreatorFactory;

// Create PSR-17 ResponseFactory
$psr17Factory = new Psr17Factory();

AppFactory::setResponseFactory($psr17Factory);

// Create the Slim app
$app = AppFactory::create();

// Load database configuration
require __DIR__ . '/../../config/database.php';

// Load sub-routes
$pokemonRoutes = require __DIR__ . '/pokemon.php';
$pokemonRoutes($app);

// Create ServerRequest from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

$app->run($request);
