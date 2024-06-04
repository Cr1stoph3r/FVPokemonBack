# Dependencias

- PHP: 8.3.7
- Composer: 2.7.6
- Slim Framework: 4.13
- Illuminate/Database (Eloquent): 11.9

# Pasos

## Paso 1: instalacion

- Clonar el repositorio localmente.
- Habilitar driver de mysql en la carpeta de instalación de php en el archivo php.ini. Se debe buscar "pdo_mysql", eliminar ";" y guardar.
- Instalar las dependencias:

```sh
composer install
```

## Paso 2: Creacion bd y conexion con bd

- Iniciamos sesion con usuario en mysql y ejecutamos el script de la raiz del proyecto llamado "schema.sql"
- Una vez creada la base de datos, a .env.example quitar ".example" y modificar los campos por los datos de las credenciales de su base de datos donde ejecuto el script de creación de la bd.

## Paso 3: Poblar tablas

- una vez hecho los cambios se debe ejecutar el script 'jobs/UpdatePokemonDB.php'

```sh
// en la raiz de proyecto ejecutar
php jobs/UpdatePokemonDB.php
```

- Este script tardara un poco ya que añade varios datos, pero una vez se comienzan a poblar los pokemon, ya se puede visualizar en front, corriendo el script en una consola y el servidor en otra.
- Al finalizar el script se finaliza poblando la tabla stats que almacena los puntos mas altos de cada habilidad del pokemon ('HP', 'Ataque', 'Defensa', etc)

### (En caso de que ocurra algun problema con el script por conexion con la api, volver a ejecutar, el script saltara los que ya se insertaron)

## Paso 4: Ejecutar servidor

- Una vez poblada la base de datos se ejecuta el servidor usando:

```sh
php -S localhost:8000 -t src
```

# Finalmente, descarga el repositorio del frontend y sigue las instrucciones en su README para conectar con este backend

[Repositorio en GitHub](https://github.com/Cr1stoph3r/FVPokemonFront)
