<?php
// controllers/TurnoCancelar.php

// Configuración de seguridad de Cookies 
ini_set('session.cookie_httponly', 1); // JS no puede leer la cookie
ini_set('session.use_only_cookies', 1); // Forzar uso de cookies

session_start();
header('Content-Type: application/json');
require_once '../models/Turno.php';

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
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se pudo cancelar (o ya estaba cancelado)']);
}
?>