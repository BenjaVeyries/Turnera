<?php
// config/conexion_db.php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=barberia;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error conexión DB: " . $e->getMessage());
}
?>