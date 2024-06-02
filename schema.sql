--Create and use db
CREATE DATABASE IF NOT EXISTS fv_pokemon_db;
use fv_pokemon_db;

-- Create tables
-- Table to store colors
CREATE TABLE color (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);


-- Table to store Pokemon habitats
CREATE TABLE habitat (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);


-- Table to store Pokemon
CREATE TABLE pokemon (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    hp INT NOT NULL DEFAULT 0,
    attack INT NOT NULL DEFAULT 0,
    defense INT NOT NULL DEFAULT 0,
    special_attack INT NOT NULL DEFAULT 0,
    special_defense INT NOT NULL DEFAULT 0,
    speed INT NOT NULL DEFAULT 0,
    weight int NOT NULL DEFAULT 0,
    evolves_from INT,
    evolution_order INT NOT NULL,
    color_id INT null,
	habitat_id INT null,
    FOREIGN KEY (evolves_from) REFERENCES pokemon(id) on DELETE SET NULL,
    FOREIGN KEY (color_id) REFERENCES color(id),
	FOREIGN KEY (habitat_id) REFERENCES habitat(id)
);

-- -- Table to store Pokemon types
CREATE TABLE type (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Table to relate Pokemon to their types
CREATE TABLE pokemon_type (
	id INT PRIMARY KEY AUTO_INCREMENT,
    pokemon_id INT,
    type_id INT,
    FOREIGN KEY (pokemon_id) REFERENCES pokemon(id),
    FOREIGN KEY (type_id) REFERENCES type(id)
);
-- Table to store the names of statistics and their maximum values
CREATE TABLE stat (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    max_value INT NOT NULL
);

-- Table to store skills
CREATE TABLE ability (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

-- Table to relate Pokémon to their abilities
CREATE TABLE pokemon_ability (
    pokemon_id INT,
    ability_id INT,
    is_hidden BOOLEAN NOT NULL,
    slot INT NOT NULL,
    PRIMARY KEY (pokemon_id, ability_id, slot),
    FOREIGN KEY (pokemon_id) REFERENCES pokemon(id),
    FOREIGN KEY (ability_id) REFERENCES ability(id)
);


-- Table to store movements
CREATE TABLE move (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Table to relate Pokémon with their movements
CREATE TABLE pokemon_move (
    pokemon_id INT,
    move_id INT,
    PRIMARY KEY (pokemon_id, move_id),
    FOREIGN KEY (pokemon_id) REFERENCES pokemon(id),
    FOREIGN KEY (move_id) REFERENCES move(id)
);