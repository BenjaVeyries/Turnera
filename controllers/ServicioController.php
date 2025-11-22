<?php
// controllers/ServicioController.php
header('Content-Type: application/json');
require_once '../models/Servicio.php';

// Obtener todos los servicios
try {
    $servicios = Servicio::obtenerTodos();
    echo json_encode($servicios);
} catch (Exception $e) {
    echo json_encode([]);
}
?>