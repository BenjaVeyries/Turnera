<?php
// controllers/TurnoController.php

// 1. CONFIGURACIÓN DE SEGURIDAD (SIEMPRE PRIMERO)
// Solo configuramos si la sesión NO ha iniciado todavía
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

header('Content-Type: application/json');

require_once '../models/Turno.php';
require_once '../models/Notificacion.php'; 

// 2. Validar CSRF
if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    echo json_encode(['status'=>'error', 'message'=>'Error de seguridad (Token inválido)']);
    exit;
}

// 3. Validar Sesión
if(!isset($_SESSION['usuario_id'])){
    echo json_encode(['status'=>'error','message'=>'No estás logueado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$fecha = $_POST['fecha'] ?? '';
$hora = $_POST['hora'] ?? '';
$servicio_id = $_POST['servicio_id'] ?? null;
$peluquero_id = $_POST['peluquero_id'] ?? null;

// 4. Validación de datos
if(!$fecha || !$hora || !$servicio_id || !$peluquero_id){
    echo json_encode(['status'=>'error','message'=>'Faltan datos de la reserva']);
    exit;
}

try {
    // 5. Crear Turno
    $creado = Turno::crear($usuario_id, $fecha, $hora, $servicio_id, $peluquero_id);

    if($creado){
        // Notificar admin
        $nombreCliente = $_SESSION['nombre'] ?? 'Cliente';
        $mensaje = "Nuevo turno: $nombreCliente reservó el $fecha a las " . substr($hora, 0, 5);
        Notificacion::notificarAdmins($mensaje);
        
        echo json_encode(['status'=>'ok']);
    } else {
        // Este es el mensaje que veías en el error JSON
        echo json_encode(['status'=>'error','message'=>'El turno ya está reservado o no disponible.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status'=>'error', 'message' => 'Error servidor: ' . $e->getMessage()]);
}
?>