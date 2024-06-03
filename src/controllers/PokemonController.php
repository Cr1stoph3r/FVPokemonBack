<?php

namespace Fv\Back\Controllers;

use Fv\Back\Models\PokemonModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;


class PokemonController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    public function getPokemons(Request $request, Response $response, $args)
    {
        try {
            $queryParams = $request->getQueryParams();
    
            $limit = isset($queryParams['limit']) ? (int)$queryParams['limit'] : 10;
            $currentPage = isset($queryParams['current_page']) ? (int)$queryParams['current_page'] : 1;
            $offset = ($currentPage - 1) * $limit;
            $filters = isset($queryParams['filters']) ? $queryParams['filters'] : [];
            $order = isset($queryParams['order']) ? $queryParams['order'] : 'asc';
            $orderBy = isset($queryParams['orderBy']) ? $queryParams['orderBy'] : 'id';
            $search = isset($queryParams['search']) ? $queryParams['search'] : null;
            
            if ($orderBy === "null") {
                $orderBy = 'id';
            }else{
                $orderBy = strtolower(str_replace(' ', '_', $orderBy));
            }

            $this->logger->info("GET /pokemon/list", [
                'limit' => $limit,
                'offset' => $offset,
                'current_page' => $currentPage,
                'filters' => $filters,
                'order' => $order,
                'order_asc' => $orderBy
            ]);
            
    
            $pokemonQuery = PokemonModel::query();

            // Añadir filtro en duro para evolves_from
            $pokemonQuery->whereNull('evolves_from');

            // Aplicar filtro de búsqueda si está presente
            if ($search) {
                $pokemonQuery->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%')
                          ->orWhereHas('evolvesTo', function ($subQuery) use ($search) {
                              $subQuery->where('name', 'LIKE', '%' . $search . '%');
                          });
                });
            }

        // Aplicar filtros de hábitats y tipos de forma inclusiva (OR)
        if (!empty($filters) && is_array($filters)) {
            $pokemonQuery->where(function ($query) use ($filters) {
                if (!empty($filters['habitats'])) {
                    $habitatIds = explode(',', $filters['habitats']);
                    $query->orWhereIn('habitat_id', $habitatIds);
                }
                if (!empty($filters['types'])) {
                    $typeIds = explode(',', $filters['types']);
                    $query->orWhereHas('types', function ($subQuery) use ($typeIds) {
                        $subQuery->whereIn('type_id', $typeIds);
                    });
                }
            });
        }

            // Obtener el total de Pokémon antes de aplicar limit y offset
            $totalPokemons = $pokemonQuery->count();
    
            // Incluir la relación con el color
            $pokemonQuery->with('color', 'types');

            // Ordenar
            $pokemonQuery->orderBy($orderBy, $order);

            // Paginación
            $pokemons = $pokemonQuery->skip($offset)->take($limit)->get();

            // Transformar los datos
            $result = $pokemons->map(function($pokemon) {
                return [
                    'id' => $pokemon->id,
                    'name' => $pokemon->name,
                    'url_img' => $pokemon->url_img,
                    'hp' => $pokemon->hp,
                    'attack' => $pokemon->attack,
                    'defense' => $pokemon->defense,
                    'color' => $pokemon->color ? $pokemon->color->name : null, // Incluir el nombre del color
                    'types' => $pokemon->types->pluck('name') // Incluir los nombres de los tipos
                ];
            });
    
            $payload = json_encode([
                'total' => $totalPokemons,
                'results' => $result
            ]);            
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        }catch (\Throwable $th) {
            $this->logger->error("GET /pokemon/list", [
                'error' => $th->getMessage()
            ]);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }

    public function getPokemonWithEvolutions(Request $request, Response $response, $args)
    {
        try {
            $pokemonId = $args['id'];

            $pokemon = PokemonModel::with(['color', 'habitat', 'types', 'abilities', 'moves', 'evolvesTo'])
                ->find($pokemonId);

            if (!$pokemon) {
                $payload = json_encode(['error' => 'Pokémon not found']);
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
            }

            $result = [
                'id' => $pokemon->id,
                'name' => $pokemon->name,
                'url_img' => $pokemon->url_img,
                'hp' => $pokemon->hp,
                'attack' => $pokemon->attack,
                'defense' => $pokemon->defense,
                'special_attack' => $pokemon->special_attack,
                'special_defense' => $pokemon->special_defense,
                'speed' => $pokemon->speed,
                'weight' => $pokemon->weight,
                'color' => $pokemon->color ? $pokemon->color->name : null,
                'habitat' => $pokemon->habitat ? $pokemon->habitat->name : null,
                'types' => $pokemon->types->pluck('name'),
                'abilities' => $pokemon->abilities->map(function ($ability) {
                    return [
                        'name' => $ability->name,
                        'is_hidden' => $ability->pivot->is_hidden,
                        'slot' => $ability->pivot->slot
                    ];
                }),
                'moves' => $pokemon->moves->pluck('name'),
                'evolutions' => $pokemon->evolvesTo->map(function ($evolution) {
                    return [
                        'id' => $evolution->id,
                        'name' => $evolution->name,
                        'url_img' => $evolution->url_img,
                        'hp' => $evolution->hp,
                        'attack' => $evolution->attack,
                        'defense' => $evolution->defense,
                        'special_attack' => $evolution->special_attack,
                        'special_defense' => $evolution->special_defense,
                        'speed' => $evolution->speed,
                        'weight' => $evolution->weight,
                        'color' => $evolution->color ? $evolution->color->name : null,
                        'habitat' => $evolution->habitat ? $evolution->habitat->name : null,
                        'types' => $evolution->types->pluck('name'),
                        'abilities' => $evolution->abilities->map(function ($ability) {
                            return [
                                'name' => $ability->name,
                                'is_hidden' => $ability->pivot->is_hidden,
                                'slot' => $ability->pivot->slot
                            ];
                        }),
                        'moves' => $evolution->moves->pluck('name')
                    ];
                })
            ];

            $payload = json_encode($result);

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Throwable $th) {
            $this->logger->error("GET /pokemon/{$args['id']}/with-evolutions", [
                'error' => $th->getMessage()
            ]);
            $payload = json_encode(['error' => 'Internal Server Error']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
