<?php
// controllers/TurnoCancelar.php
session_start();
header('Content-Type: application/json');
require_once '../models/Turno.php';

// 1. Seguridad: Login
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

// 2. Recibir ID
$idTurno = $_POST['id'] ?? null;

if (!$idTurno) {
    echo json_encode(['status' => 'error', 'message' => 'Falta ID del turno']);
    exit;
}

// 3. Llamar al Modelo
if (Turno::cancelarPorCliente($idTurno, $_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se pudo cancelar (o ya estaba cancelado)']);
}
?>