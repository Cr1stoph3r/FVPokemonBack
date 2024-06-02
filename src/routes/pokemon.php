<?php

use Fv\Back\controllers\PokemonController;
use Slim\Routing\RouteCollectorProxy;

return function ($app) {
    $app->group('/pokemon', function (RouteCollectorProxy $group) {
        $group->get('/', PokemonController::class . ':getPokemons');
        // Otras rutas específicas para Pokémon
    });
};
