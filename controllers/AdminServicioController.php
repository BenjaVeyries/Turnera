<?php
session_start();
require_once '../models/Servicio.php';

// Seguridad Admin
if ($_SESSION['rol'] !== 'Administrador') die("Acceso denegado");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'crear_servicio') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $duracion = $_POST['duracion'];
    
    if(Servicio::crear($nombre, $precio, $duracion)) {
        header("Location: ../views/admin_servicios.php?status=ok");
    } else {
        echo "Error al guardar";
    }
}
?>