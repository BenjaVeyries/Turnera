<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['usuario_id'])){
    echo json_encode(['status'=>'error','message'=>'No estás logueado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$fecha = $_POST['fecha'] ?? '';
$hora = $_POST['hora'] ?? '';

// Validar campos obligatorios
if(!$fecha || !$hora){
    echo json_encode(['status'=>'error','message'=>'Completa todos los campos']);
    exit;
}

try{
    $pdo = new PDO("mysql:host=localhost;dbname=barberia;charset=utf8","root","");
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    // Validar disponibilidad
    $stmt = $pdo->prepare("SELECT id FROM turnos WHERE fecha=? AND hora=?");
    $stmt->execute([$fecha, $hora]);

    if($stmt->fetch()){
        echo json_encode(['status'=>'error','message'=>'El turno ya está reservado. Por favor elige otro.']);
        exit;
    }

    // Insertar turno sin servicio
    $stmt = $pdo->prepare("INSERT INTO turnos(usuario_id, fecha, hora) VALUES(?,?,?)");
    $stmt->execute([$usuario_id, $fecha, $hora]);

    echo json_encode(['status'=>'ok']);
}catch(PDOException $e){
    echo json_encode(['status'=>'error','message'=>'Error al guardar turno']);
}



