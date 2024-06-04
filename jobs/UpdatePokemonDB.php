<?php
require __DIR__ . '/../vendor/autoload.php';

// Load environment variables from the .env file in the project root
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

require __DIR__ . '/../config/database.php';

use Fv\Back\Models\TypeModel;
use Fv\Back\Models\HabitatModel;
use Fv\Back\Models\AbilityModel;
use Fv\Back\Models\MovesModel;
use Fv\Back\Models\ColorsModel;
use Fv\Back\Models\PokemonModel;
use Fv\Back\Models\PokemonAbilityModel;
use Fv\Back\Models\PokemonMoveModel;
use Fv\Back\Models\PokemonTypeModel;
use Fv\Back\Models\StatsModel;


$base_url_pokeapi= 'https://pokeapi.co/api/v2/';
$context = stream_context_create(['http' => ['timeout' => 20]]);

function extractIdFromUrl($url) {
    return explode('/', rtrim($url, '/'))[count(explode('/', rtrim($url, '/')))-1];
}

// Start block.
//------------------------------------------------------------------------------------------------------------//
// Functions to get all types of pokemon and insert them into the db
//------------------------------------------------------------------------------------------------------------//
$cont_new_types = 0;
// Function that gets all types of pokemon.

function getPokemonTypes() {
    global $base_url_pokeapi, $context;
    try {
        $url_types = $base_url_pokeapi."type";

        // Gets the total number of pokemon types
        $count = json_decode(file_get_contents($url_types, false, $context), true)['count'] ?? 0;

        if($count == 0) return [];
        
        // Gets all pokemon types
        $url_all_types = "$url_types?limit=$count";

        $pokemon_types = json_decode(file_get_contents($url_all_types, false, $context), true)["results"] ?? [];

        return $pokemon_types;

    } catch (\Throwable $th) {
        echo ''. $th->getMessage() .'';
        echo " | Error in function: " . __FUNCTION__;
        echo " | Please try running the script again.";
        exit();
    }
}

// Function that checks if any Pokémon type is not registered in the database and inserts it 
function insertPokemonTypes($pokemon_types) {
    global $cont_new_types;
    try {
        foreach ($pokemon_types as $pk_tp) {
            // Extract the id from the url
            $id_type = extractIdFromUrl($pk_tp['url']);
            $name = $pk_tp["name"];

            //check if the type id is in the table

            $exists = TypeModel::where("id", $id_type)->first();
            if ($exists) {
                continue;
            }
            // insert id and name into types table
            $new_pokemon_type = new TypeModel();
            $new_pokemon_type->id = $id_type;
            $new_pokemon_type->name = $name;
            $new_pokemon_type->save();
            $cont_new_types++;
        };
    } catch (\Throwable $th) {
        echo ''. $th->getMessage() .'';
        echo " | Error in function: " . __FUNCTION__;
        echo " | Please try running the script again.";
        exit();
    }
};

$pokemon_types = getPokemonTypes();

if(empty($pokemon_types)) {
    echo "No se encontraron tipos de pokemon. Se finaliza el proceso. Ejecutar más tarde el script.\n";
    exit();
};

insertPokemonTypes($pokemon_types);

if($cont_new_types == 0) {
    echo "No se encontraron nuevos tipos de pokemon. \n"; 
}else{
    echo "Se insertaron ".$cont_new_types." tipos de pokemon en la base de datos.\n";
}

//------------------------------------------------------------------------------------------------------------//
// End block

//------------------------------------------------------------------------------------------------------------//

// Start block.
//------------------------------------------------------------------------------------------------------------//
// Functions to gets all the habitats from the pokeapi and insert them into the database
//------------------------------------------------------------------------------------------------------------//
$cont_new_habitats = 0;

function getPokemonHabitats() {
    global $base_url_pokeapi, $context;
    try {
        $url_habitats = $base_url_pokeapi."pokemon-habitat";

        // Gets the total number of pokemon types
        $count = json_decode(file_get_contents($url_habitats, false, $context), true)['count'] ?? 0;

        if($count == 0) return [];
        
        // Gets all pokemon types
        $url_all_habitats = "$url_habitats?limit=$count";

        $pokemon_habitats = json_decode(file_get_contents($url_all_habitats, false, $context), true)["results"] ?? [];

        // echo "<pre>";
        // print_r($pokemon_habitats);
        // echo "</pre>";
        return $pokemon_habitats;

    } catch (\Throwable $th) {
        echo ''. $th->getMessage() .'';
        echo " | Error in function: " . __FUNCTION__;
        echo " | Please try running the script again.";
        exit();
    }
}

// Function that checks if any Pokémon habitat is not registered in the database and inserts it 
function insertPokemonHabitats($pokemon_habitats) {
    global $cont_new_habitats;
    try {
        foreach ($pokemon_habitats as $pk_hb) {
            // Extract the id from the url
            $id_habitat = extractIdFromUrl($pk_hb['url']);
            $name = $pk_hb["name"];

            //check if the habitat id is in the table

            $exists = HabitatModel::where("id", $id_habitat)->first();
            if ($exists) {
                continue;
            }

            // insert id and name into habitat table
            $new_pokemon_habitat = new HabitatModel();
            $new_pokemon_habitat->id = $id_habitat;
            $new_pokemon_habitat->name = $name;
            $new_pokemon_habitat->save();
            $cont_new_habitats++;
        };
    } catch (\Throwable $th) {
        echo ''. $th->getMessage() .'';
        echo " | Error in function: " . __FUNCTION__;
        echo " | Please try running the script again.";
        exit();
    }
};

$pokemon_habitats = getPokemonHabitats();


if(empty($pokemon_habitats)) {
    echo "No se encontraron habitats de pokemon. Se finaliza el proceso. Ejecutar más tarde el script.\n";
    exit();
};

insertPokemonHabitats($pokemon_habitats);

if($cont_new_habitats == 0) {
    echo "No se encontraron nuevos habitats de pokemon. \n"; 
}else{
    echo "Se insertaron ".$cont_new_habitats." habitats de pokemon en la base de datos.\n";
}


//------------------------------------------------------------------------------------------------------------//
// End block

//------------------------------------------------------------------------------------------------------------//

// Start block.
//------------------------------------------------------------------------------------------------------------//
// Functions to gets all the abilities from the pokeapi and insert them into the database
//------------------------------------------------------------------------------------------------------------//
$cont_new_ability = 0;

function getPokemonAbility() {
    global $base_url_pokeapi, $context;
    try {
        $url_abilities = $base_url_pokeapi."ability";

        // Gets the total number of pokemon abilities
        $count = json_decode(file_get_contents($url_abilities, false, $context), true)['count'] ?? 0;

        if($count == 0) return [];
        
        // Gets all pokemon abilities
        $url_all_abilities = "$url_abilities?limit=$count";

        $pokemon_abilities = json_decode(file_get_contents($url_all_abilities, false, $context), true)["results"] ?? [];

        // echo "<pre>";
        // print_r($pokemon_abilities);
        // echo "</pre>";
        return $pokemon_abilities;

    } catch (\Throwable $th) {
        echo ''. $th->getMessage() .'';
        echo " | Error in function: " . __FUNCTION__;
        echo " | Please try running the script again.";
        exit();
    }
}

// Function that checks if any Pokémon ailibity is not registered in the database and inserts it 
function insertPokemonAbility($pokemon_ability) {
    global $cont_new_ability;
    try {
        foreach ($pokemon_ability as $pk_ab) {
            // Extract the id from the url
            $id_ability = extractIdFromUrl($pk_ab["url"]);
            $name = $pk_ab["name"];

            //check if the ability id is in the table

            $exists = AbilityModel::where("id", $id_ability)->first();
            if ($exists) {
                continue;
            }

            // insert id and name into ability table
            $new_pokemon_ability = new AbilityModel();
            $new_pokemon_ability->id = $id_ability;
            $new_pokemon_ability->name = $name;
            $new_pokemon_ability->save();
            $cont_new_ability++;
        };
    } catch (\Throwable $th) {
        echo ''. $th->getMessage() .'';
        echo " | Error in function: " . __FUNCTION__;
        echo " | Please try running the script again.";
        exit();
    }
};

$pokemon_ability = getPokemonAbility();


if(empty($pokemon_ability)) {
    echo "No se encontraron habilidades de pokemon. Se finaliza el proceso. Ejecutar más tarde el script.\n";
    exit();
};

insertPokemonAbility($pokemon_ability);

if($cont_new_ability == 0) {
    echo "No se encontraron nuevas habilidades de pokemon. \n"; 
}else{
    echo "Se insertaron ".$cont_new_ability." habilidades de pokemon en la base de datos.\n";
}


//------------------------------------------------------------------------------------------------------------//
// End block

//------------------------------------------------------------------------------------------------------------//

// Start block.
//------------------------------------------------------------------------------------------------------------//
// Functions to gets all the moves from the pokeapi and insert them into the database
//------------------------------------------------------------------------------------------------------------//
$cont_new_moves = 0;

function getPokemonMoves() {
    global $base_url_pokeapi, $context;
    try {
        $url_moves = $base_url_pokeapi."move";

        // Gets the total number of pokemon moves
        $count = json_decode(file_get_contents($url_moves, false, $context), true)['count'] ?? 0;

        if($count == 0) return [];
        
        // Gets all pokemon moves
        $url_all_moves = "$url_moves?limit=$count";

        $pokemon_moves = json_decode(file_get_contents($url_all_moves, false, $context), true)["results"] ?? [];

        // echo "<pre>";
        // print_r($pokemon_moves);
        // echo "</pre>";
        return $pokemon_moves;

    } catch (\Throwable $th) {
        echo ''. $th->getMessage() .'';
        echo " | Error in function: " . __FUNCTION__;
        echo " | Please try running the script again.";
        exit();
    }
}

// Function that checks if any Pokémon move is not registered in the database and inserts it

function insertPokemonMoves($pokemon_moves) {
    global $cont_new_moves;
    try {
        foreach ($pokemon_moves as $pk_mv) {
            // Extract the id from the url
            $id_move = extractIdFromUrl($pk_mv["url"]);
            $name = $pk_mv["name"];

            //check if the move id is in the table

            $exists = MovesModel::where("id", $id_move)->first();
            if ($exists) {
                continue;
            }

            // insert id and name into move table
            $new_pokemon_move = new MovesModel();
            $new_pokemon_move->id = $id_move;
            $new_pokemon_move->name = $name;
            $new_pokemon_move->save();
            $cont_new_moves++;
        };
    } catch (\Throwable $th) {
        echo ''. $th->getMessage() .'';
        echo " | Error in function: " . __FUNCTION__;
        echo " | Please try running the script again.";
        exit();
    }
};

$pokemon_moves = getPokemonMoves();

if(empty($pokemon_moves)) {
    echo "No se encontraron movimientos de pokemon. Se finaliza el proceso. Ejecutar más tarde el script.\n";
    exit();
};

insertPokemonMoves($pokemon_moves);

if($cont_new_moves == 0) {
    echo "No se encontraron nuevos movimientos de pokemon. \n";
}else{
    echo "Se insertaron ".$cont_new_moves." movimientos de pokemon en la base de datos.\n";
}

//------------------------------------------------------------------------------------------------------------//
// End block

//------------------------------------------------------------------------------------------------------------//

// Start block.
//------------------------------------------------------------------------------------------------------------//
// Functions to gets all the colors from the pokeapi and insert them into the database
//------------------------------------------------------------------------------------------------------------//
$cont_new_colors = 0;

function getPokemonColors() {
    global $base_url_pokeapi, $context;
    try {
        $url_colors = $base_url_pokeapi."pokemon-color";

        // Gets the total number of pokemon colors
        $count = json_decode(file_get_contents($url_colors, false, $context), true)['count'] ?? 0;

        if($count == 0) return [];
        
        // Gets all pokemon colors
        $url_all_colors = "$url_colors?limit=$count";

        $pokemon_colors = json_decode(file_get_contents($url_all_colors, false, $context), true)["results"] ?? [];

        // echo "<pre>";
        // print_r($pokemon_colors);
        // echo "</pre>";
        return $pokemon_colors;

    } catch (\Throwable $th) {
        echo ''. $th->getMessage() .'';
        echo " | Error in function: " . __FUNCTION__;
        echo " | Please try running the script again.";
        exit();
    }
}

// Function that checks if any Pokémon color is not registered in the database and inserts it

function insertPokemonColors($pokemon_colors) {
    global $cont_new_colors;
    try {
        foreach ($pokemon_colors as $pk_cl) {
            // Extract the id from the url
            $id_color = extractIdFromUrl($pk_cl['url']);
            $name = $pk_cl["name"];

            //check if the color id is in the table

            $exists = ColorsModel::where("id", $id_color)->first();
            if ($exists) {
                continue;
            }

            // insert id and name into color table
            $new_pokemon_color = new ColorsModel();
            $new_pokemon_color->id = $id_color;
            $new_pokemon_color->name = $name;
            $new_pokemon_color->save();
            $cont_new_colors++;
        };
    } catch (\Throwable $th) {
        echo ''. $th->getMessage() .'';
        echo " | Error in function: " . __FUNCTION__;
        echo " | Please try running the script again.";
        exit();
    }
};

$pokemon_colors = getPokemonColors();

if(empty($pokemon_colors)) {
    echo "No se encontraron colores de pokemon. Se finaliza el proceso. Ejecutar más tarde el script.\n";
    exit();
};

insertPokemonColors($pokemon_colors);

if($cont_new_colors == 0) {
    echo "No se encontraron nuevos colores de pokemon. \n";
}else{
    echo "Se insertaron ".$cont_new_colors." colores de pokemon en la base de datos.\n";
}

//------------------------------------------------------------------------------------------------------------//
// End block

//------------------------------------------------------------------------------------------------------------//

// Start block.
//------------------------------------------------------------------------------------------------------------//
// Functions to gets all the pokemons from the pokeapi and insert them into the database
//------------------------------------------------------------------------------------------------------------//
$cont_new_pokemons = 0;

function getPokemons() {
    global $base_url_pokeapi, $context;
    try {
        $url_pokemons = $base_url_pokeapi."evolution-chain";

        // Gets the total number of pokemon
        $count = json_decode(file_get_contents($url_pokemons, false, $context), true)['count'] ?? 0;

        if($count == 0) return [];
        
        // Gets all pokemon
        $url_all_pokemons = "$url_pokemons?limit=$count";

        $pokemon = json_decode(file_get_contents($url_all_pokemons, false, $context), true)["results"] ?? [];
        return $pokemon;

    } catch (\Throwable $th) {
        echo ''. $th->getMessage() .'';
        echo " | Error in function: " . __FUNCTION__;
        echo " | Please try running the script again.";
        exit();
    }
}


function insertPokemon($pokemon, $order, $id_evolves_from = null) {
    global $cont_new_pokemons, $context;
    $evolves_from = $id_evolves_from;//
    $evolution_order = $order;//
    try {
        $name = $pokemon["name"];
        // Get the pokemon specie details
        $pokemon_specie = json_decode(file_get_contents($pokemon["url"], false, $context), true);
        if(!$pokemon_specie) {
            echo "No se encontraron detalles de especie del pokemon: ".$name."\n";
            return 0;
        };

        if($pokemon_specie["color"] != null){
            $color_url = $pokemon_specie["color"]["url"];
            $color_id = extractIdFromUrl($color_url) ?? null;
        }else{
            $color_id = null;
        }

        if($pokemon_specie["habitat"] != null){
            $habitat_url = $pokemon_specie["habitat"]["url"];
            $habitat_id = extractIdFromUrl($habitat_url) ?? null;
        }else{
            $habitat_id = null;
        }

        // Get the pokemon details
        
        $pokemon_details = json_decode(file_get_contents($pokemon_specie["varieties"][0]["pokemon"]["url"], false, $context), true);
        if(!$pokemon_details) {
            echo "No se encontraron detalles del pokemon: ".$name."\n";
            return 0;
        };

        // Get the stats of the pokemon
        $stats = $pokemon_details["stats"];
        $stat_values = [];
        
        foreach ($stats as $stat) {
            $stat_name = $stat["stat"]["name"];
            $stat_values[$stat_name] = $stat["base_stat"];
        }
        
        $hp = $stat_values["hp"];
        $attack = $stat_values["attack"];
        $defense = $stat_values["defense"];
        $special_attack = $stat_values["special-attack"];
        $special_defense = $stat_values["special-defense"];
        $speed = $stat_values["speed"];

        $id = $pokemon_details["id"];
        $weight = $pokemon_details["weight"];
        $url_img = $pokemon_details["sprites"]["other"]["official-artwork"]["front_default"] ?? null;

        echo "ID: " .$id."\n";
        echo "Name: " .$name."\n";
        echo "-----------------------------------\n";

        // Insert into the database using Eloquent
        $pokemonModel = new PokemonModel();
        $pokemonModel->id = $id;
        $pokemonModel->name = $name;
        $pokemonModel->hp = $hp;
        $pokemonModel->attack = $attack;
        $pokemonModel->defense = $defense;
        $pokemonModel->special_attack = $special_attack;
        $pokemonModel->special_defense = $special_defense;
        $pokemonModel->speed = $speed;
        $pokemonModel->weight = $weight;
        $pokemonModel->url_img = $url_img;
        $pokemonModel->evolves_from = $evolves_from;
        $pokemonModel->evolution_order = $evolution_order;
        $pokemonModel->color_id = $color_id;
        $pokemonModel->habitat_id = $habitat_id;

        if ($pokemonModel->save()) {
            echo "Pokémon insertado exitosamente: ".$name."\n";
            // Insert Pokémon types
            foreach ($pokemon_details["types"] as $type) {
                $type_id = extractIdFromUrl($type["type"]["url"]);
                PokemonTypeModel::create([
                    'pokemon_id' => $id,
                    'type_id' => $type_id
                ]);
            }

            // Insert pokemon abilities
            foreach ($pokemon_details["abilities"] as $ability) {
                $ability_id = extractIdFromUrl($ability["ability"]["url"]);
                PokemonAbilityModel::create([
                    'pokemon_id' => $id,
                    'ability_id' => $ability_id,
                    'is_hidden' => $ability["is_hidden"],
                    'slot' => $ability["slot"]
                ]);
            }

            // Insert pokemon moves
            foreach ($pokemon_details["moves"] as $move) {
                $move_id = extractIdFromUrl($move["move"]["url"]);
                PokemonMoveModel::create([
                    'pokemon_id' => $id,
                    'move_id' => $move_id
                ]);
            }
        } else {
            echo "Error al insertar el Pokémon: ".$name."\n";
        }

        return $id;

    } catch (\Throwable $th) {
        echo ''. $th->getMessage() .'';
        echo " | Error in function: " . __FUNCTION__;
        echo " | Please try running the script again.";
        exit();
    }
};
function handleEvolution($evolution, $id_evolves_from = null, $evolution_order = 1, $context) {
    if ($evolution === null) return; // Stops the recursion if there is no evolution

    $pokemon = $evolution["species"];
    $id_pokemon_url = extractIdFromUrl($pokemon["url"]) ?? null;
    $existingPokemon = PokemonModel::find($id_pokemon_url);

    $pokemon_id = 0; 
    if ($existingPokemon) {
        echo "The Pokémon with ID: " . $id_pokemon_url . " is already inserted.\n";
        if($evolution_order == 1){
            $pokemon_id = $id_pokemon_url;
        }else{
            $pokemon_id = $id_evolves_from;
        }
        $newPokemonId = $pokemon_id;
    } else {
        $newPokemonId = insertPokemon($pokemon, $evolution_order, $id_evolves_from);
        if($evolution_order == 1){
            $pokemon_id = $newPokemonId;
        }else{
            $pokemon_id = $id_evolves_from;
        };

    }

    // if pokemon id is 0, return null
    if ($newPokemonId == 0) return null;

    // Process the next level of evolution
    if (!empty($evolution["evolves_to"])) {
        foreach ($evolution["evolves_to"] as $nextEvolution) {
            // The first level of evolution passes 'null' as evolves_from,
            // the following levels pass the ID of the base species of the current level
            handleEvolution($nextEvolution, $pokemon_id, $evolution_order + 1, $context);
        }
    }
}

function processEvolutions($pokemon_evolutions) {
    global $context;
    try {
        foreach ($pokemon_evolutions as $pk) {
            $url_pokemon = $pk["url"];
            $pokemon_evolutions = json_decode(file_get_contents($url_pokemon, false, $context), true) ?? [];

            $rootEvolution = $pokemon_evolutions["chain"];
            handleEvolution($rootEvolution, null, 1, $context);
        };
    } catch (\Throwable $th) {
        echo ''. $th->getMessage() .'';
        echo " | Error in function: " . __FUNCTION__;
        echo " | Please try running the script again.";
        exit();
    }
}





$pokemons = getPokemons();

if(empty($pokemons)) {
    echo "No se encontraron pokemons. Se finaliza el proceso. Ejecutar más tarde el script.\n";
    exit();
};

processEvolutions($pokemons);

if($cont_new_pokemons == 0) {
    echo "No se encontraron nuevos pokemons. \n";
}else{
    echo "Se insertaron ".$cont_new_pokemons." pokemons en la base de datos.\n";
}

//------------------------------------------------------------------------------------------------------------//
// Start block.
//------------------------------------------------------------------------------------------------------------//
// Function to get the highest value of each stat from all Pokémon and insert them into the stats table
//------------------------------------------------------------------------------------------------------------//
function insertMaxStats() {
    try {
        $stats = [
            'hp' => 'HP',
            'attack' => 'Attack',
            'defense' => 'Defense',
            'special_attack' => 'Special Attack',
            'special_defense' => 'Special Defense',
            'speed' => 'Speed'
        ];

        foreach ($stats as $column => $name) {
            $maxValue = PokemonModel::max($column);
            StatsModel::updateOrCreate(
                ['name' => $name],
                ['max_value' => $maxValue]
            );
        }

        echo "Max values inserted into stats table.\n";
    } catch (\Throwable $th) {
        echo ''. $th->getMessage() .'';
        echo " | Error in function: " . __FUNCTION__;
        echo " | Please try running the script again.";
        exit();
    }
}
insertMaxStats();
//------------------------------------------------------------------------------------------------------------//
// End block


