<?php

use DI\Container;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;
use Slim\Factory\AppFactory;
use Nyholm\Psr7\Factory\Psr17Factory;
use Slim\Factory\ServerRequestCreatorFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// Create DI Container
$container = new Container();

// Create the Slim app with the container
AppFactory::setContainer($container);
AppFactory::setResponseFactory(new Psr17Factory());
$app = AppFactory::create();

// Create the logger and configure it to log to stderr
$logger = new Logger('pokemon_logger');
$logger->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));

// Add the logger to the container
$container->set(LoggerInterface::class, $logger);

// Add middleware to handle CORS
$app->add(function (Request $request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// Route to handle OPTIONS requests
$app->options('/{routes:.+}', function (Request $request, Response $response, array $args) {
    return $response;
});

// Load sub-routes
$pokemonRoutes = require __DIR__ . '/pokemon.php';
$pokemonRoutes($app);

$statsRoutes = require __DIR__ . '/stats.php';
$statsRoutes($app);

$habitatRoutes = require __DIR__ . '/habitat.php';
$habitatRoutes($app);

$typeRoutes = require __DIR__ . '/types.php';
$typeRoutes($app);

// Create ServerRequest from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Run the app
$app->run($request);

