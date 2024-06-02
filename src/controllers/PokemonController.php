<?php

namespace Fv\Back\Controllers;

use Fv\Back\Models\Pokemon;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PokemonController
{
public function getPokemons(Request $request, Response $response, $args)
{
    $response->getBody()->write("Hola mundo");
    return $response;
}

    // Otros métodos para filtrar, ordenar y obtener detalles
}
