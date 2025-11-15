<?php 

// Pega estas 3 líneas en la parte superior
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// El resto de tu código...
spl_autoload_register(function ($class) {
    if (strpos($class, 'Model\\') === 0) {
        $class = str_replace('Model\\', '', $class);
        require_once __DIR__ . '/../models/' . $class . '.php';
    }
});


use Model\ActiveRecord;
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require 'funciones.php';
require 'database.php';


// Conectarnos a la base de datos
ActiveRecord::setDB($db);