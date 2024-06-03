<?php

use Fv\Back\Controllers\HabitatController;
use Slim\Routing\RouteCollectorProxy;

return function ($app) {
    $app->group('/habitats', function (RouteCollectorProxy $group) {
        $group->get('/select', HabitatController::class . ':getAllHabitats');
    });
};
