<?php
// controllers/PeluqueroController.php
header('Content-Type: application/json');
require_once '../models/Peluquero.php';

$servicio_id = $_GET['servicio_id'] ?? null;

try {
    if ($servicio_id) {
        // Si nos piden por servicio, filtramos
        $peluqueros = Peluquero::obtenerPorServicio($servicio_id);
    } else {
        // Sino, devolvemos todos (por las dudas)
        $peluqueros = Peluquero::obtenerTodos();
    }
    echo json_encode($peluqueros);
} catch (Exception $e) {
    echo json_encode([]);
}
?>