<?php

use Fv\Back\Controllers\TypeController;
use Slim\Routing\RouteCollectorProxy;

return function ($app) {
    $app->group('/types', function (RouteCollectorProxy $group) {
        $group->get('/select', TypeController::class . ':getAllTypes');
    });
};
