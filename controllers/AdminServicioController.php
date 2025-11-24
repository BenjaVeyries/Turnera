<?php
require_once '../config/session_start.php';
require_once '../models/Servicio.php';

if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    die("Error de seguridad: Token inválido");
}

// Seguridad Admin
if ($_SESSION['rol'] !== 'Administrador') die("Acceso denegado");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'crear_servicio') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $duracion = $_POST['duracion'];
    $descripcion = $_POST['descripcion'] ?? ''; 
    
    // Pasamos la descripción al modelo
    if(Servicio::crear($nombre, $precio, $duracion, $descripcion)) {
        header("Location: ../views/admin_servicios.php?status=ok");
    } else {
        echo "Error al guardar";
    }
}
?>