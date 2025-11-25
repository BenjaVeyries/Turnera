<?php
// config/conexion_db.php

// 1. Cargar el "autoloader" de Composer
// Como estamos en la carpeta 'config', tenemos que subir un nivel (..) para encontrar 'vendor'
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

try {
    // 2. Cargar las variables del archivo .env
    // Le decimos que busque el archivo .env en la carpeta raíz (un nivel arriba de config)
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    // 3. Obtener las variables
    $host = $_ENV['DB_HOST'];
    $db   = $_ENV['DB_NAME'];
    $user = $_ENV['DB_USER'];
    $pass = $_ENV['DB_PASS'];
    $charset = 'utf8mb4';

    // 4. Crear la conexión usando las variables
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Exception $e) {
    // En producción, no muestres $e->getMessage() al usuario, solo un mensaje genérico.
    // Pero para el TP está bien así para depurar.
    die("Error conexión DB: " . $e->getMessage());
}
?>