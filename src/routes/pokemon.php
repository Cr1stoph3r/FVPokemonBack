<?php

use Fv\Back\controllers\PokemonController;
use Slim\Routing\RouteCollectorProxy;

return function ($app) {
    $app->group('/pokemon', function (RouteCollectorProxy $group) {
        $group->get('/list', PokemonController::class . ':getPokemons');
        $group->get('/{id}/with-evolutions', PokemonController::class . ':getPokemonWithEvolutions');
    });
};
