<?php
// controllers/TurnoCancelar.php

require_once '../config/session_start.php';
header('Content-Type: application/json');
require_once '../models/Turno.php';
require_once '../models/Notificacion.php';

if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
    echo json_encode(['status'=>'error', 'message'=>'Error de seguridad (Token inválido)']);
    exit;
}

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
    $nombre = $_SESSION['nombre'];
    Notificacion::notificarAdmins("El cliente $nombre canceló su turno (ID: $idTurno).");
    
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se pudo cancelar (o ya estaba cancelado)']);
}
?>