<?php
session_start();
header('Content-Type: application/json');
require_once '../models/Turno.php';

if(!isset($_SESSION['usuario_id'])){
    echo json_encode(['status'=>'error','message'=>'No estás logueado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$fecha = $_POST['fecha'] ?? '';
$hora = $_POST['hora'] ?? '';

if(!$fecha || !$hora){
    echo json_encode(['status'=>'error','message'=>'Completa todos los campos']);
    exit;
}

try {
    // INTENTAMOS crear el turno
    $creado = Turno::crear($usuario_id, $fecha, $hora);

    if($creado){
        echo json_encode(['status'=>'ok']);
    } else {
        echo json_encode(['status'=>'error','message'=>'El turno ya está reservado.']);
    }
} catch (Exception $e) {
    // SI FALLA (ej: base de datos), devolvemos el error en JSON
    // Esto evitará el error "<br /> <b>"
    http_response_code(500); // Error de servidor
    echo json_encode(['status'=>'error', 'message' => 'Error del sistema: ' . $e->getMessage()]);
}
?>