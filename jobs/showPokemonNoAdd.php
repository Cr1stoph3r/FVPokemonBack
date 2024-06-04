<?php
require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

require __DIR__ . '/../config/database.php';

use Fv\Back\Models\PokemonModel;

$base_url_pokeapi = 'https://pokeapi.co/api/v2/';
$context = stream_context_create(['http' => ['timeout' => 20]]);

function getAllPokemons() {
    global $base_url_pokeapi, $context;

    // Get total count of Pokémon
    $url_pokemons = $base_url_pokeapi . "pokemon";
    $total_count = json_decode(file_get_contents($url_pokemons, false, $context), true)['count'] ?? 0;
    
    if ($total_count == 0) return [];

    // Fetch all Pokémon using the total count
    $all_pokemon_url = "{$url_pokemons}?limit={$total_count}";
    $all_pokemons = json_decode(file_get_contents($all_pokemon_url, false, $context), true)['results'] ?? [];
    
    return $all_pokemons;
}

function checkAndCollectUnregisteredPokemons($all_pokemons) {
    $unregistered_pokemons = [];

    foreach ($all_pokemons as $pokemon) {
        $pokemon_id = explode('/', rtrim($pokemon['url'], '/'))[count(explode('/', rtrim($pokemon['url'], '/'))) - 1];

        // Check if the Pokémon is already registered in the database
        $exists = PokemonModel::where('id', $pokemon_id)->first();

        if (!$exists) {
            $unregistered_pokemons[] = [
                'id' => $pokemon_id,
                'name' => $pokemon['name']
            ];
        }
    }

    return $unregistered_pokemons;
}

$all_pokemons = getAllPokemons();
if (empty($all_pokemons)) {
    echo "No se encontraron Pokémon. Intenta ejecutar el script más tarde.\n";
    exit();
}

$unregistered_pokemons = checkAndCollectUnregisteredPokemons($all_pokemons);

if (empty($unregistered_pokemons)) {
    echo "Todos los Pokémon ya están registrados en la base de datos.\n";
} else {
    echo "Se encontraron " . count($unregistered_pokemons) . " Pokémon no registrados.\n";

    foreach ($unregistered_pokemons as $unreg_pokemon) {
        echo "ID: " . $unreg_pokemon['id'] . ", Name: " . $unreg_pokemon['name'] . "\n";
    }
}
