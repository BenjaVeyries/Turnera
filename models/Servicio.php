<?php
require_once __DIR__ . '/../config/conexion_db.php';

class Servicio {
    // Obtener todos los servicios activos
    public static function obtenerTodos() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM servicios ORDER BY nombre ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener uno por ID (Para calcular precio final)
    public static function obtenerPorId($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nuevo servicio (Para el Admin)
    public static function crear($nombre, $precio, $duracion, $descripcion = '') {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO servicios (nombre, precio, duracion_estimada, descripcion) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nombre, $precio, $duracion, $descripcion]);
    }
    
    // Eliminar servicio
    public static function eliminar($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM servicios WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>