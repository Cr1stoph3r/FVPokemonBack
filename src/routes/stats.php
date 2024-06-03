<?php

use Fv\Back\Controllers\StatsController;
use Slim\Routing\RouteCollectorProxy;

return function ($app) {
    $app->group('/stats', function (RouteCollectorProxy $group) {
        $group->get('/select', StatsController::class . ':getAllStats');
    });
};
