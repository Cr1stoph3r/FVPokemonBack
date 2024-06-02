# Dependencias

- PHP: 8.3.7
- Composer: 2.7.6
- Slim Framework: 4.13
- Illuminate/Database (Eloquent): 11.9

# Pasos

## Paso 1:

- Clonar el repositorio localmente.
- Habilitar driver de mysql en la carpeta de instalación de php en el archivo php.ini. Se debe buscar "pdo_mysql" y eliminar ";" y guardar.
- Instalar las dependencias:

```sh
composer install
```

// // Gets the total number of Pokemon.
// function getTotalPokemons($url) {
// try {
// $context = stream_context_create(['http' => ['timeout' => 20]]);

// $response = file_get_contents($url, false, $context);

// $data = json_decode($response, true);

// // Check if the request was successful
// if ($data && isset($data['count'])) {
// return $data['count'];
// } else {
// return 0;
// }
// } catch (\Throwable $th) {
// echo $th->getMessage();
// echo " | Error in function: " . **FUNCTION**;
// return 0;
// }
// }

// //Gets all pokemon.
// function getPokemonsAPI($url_base = "", $limit = 10) {
//     try {
//         $url = "$url_base?limit=$limit";
//         $context = stream_context_create(['http' => ['timeout' => 20]]);
//         $response = file_get_contents($url, false, $context);

// if ($response === false) {
// echo "Failed to retrieve data";
// return [];
// }

// $data = json_decode($response, true);

// return $data['results'] ?? [];
// } catch (\Throwable $th) {
// echo $th->getMessage();
// echo '| Error in function:'. **FUNCTION**;
// return [];
// }
// };

// function processPokemons($pokemons) {
//     try {
//         $context = stream_context_create(['http' => ['timeout' => 20]]);
//         foreach ($pokemons as $pokemon) {
//             $evolutionChain = json_decode(file_get_contents($pokemon['url'], false, $context), true);

// }
// } catch (\Throwable $th) {
// echo $th->getMessage();
// echo ' | Error in function: '. **FUNCTION**;
// }
// }

// $totalPokemons = getTotalPokemons($base_url_pokeapi);
// if ($totalPokemons == 0) {
//     // Exit the program if there are no pokemons
//     echo "No hay pokemones";
//     exit();
// }
// $pokemons = getPokemonsAPI($base_url_pokeapi,$totalPokemons);
// if (empty($pokemons)) {
// echo "No se encontraron pokemons";
// exit();
// }

// processPokemons($pokemons);
