<?php
session_start();
require_once '../models/Turno.php'; // Importar Modelo

if(!isset($_SESSION['usuario_id'])){
    http_response_code(403);
    exit;
}

$fecha = $_GET['fecha'] ?? '';
if(!$fecha){
    echo json_encode([]);
    exit;
}

// Generar horas totales
$inicio = new DateTime('09:00');
$fin = new DateTime('18:00');
$horas_disponibles = [];
while($inicio <= $fin){
    $horas_disponibles[] = $inicio->format('H:i:s');
    $inicio->modify('+30 minutes');
}

// USAR EL MODELO en vez de SQL directo
$ocupadas = Turno::obtenerOcupadas($fecha); // <--- Asegurate de tener esta función en Turno.php

$libres = array_diff($horas_disponibles, $ocupadas);
echo json_encode(array_values($libres));
?>