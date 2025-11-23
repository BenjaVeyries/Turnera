<?php

// Configuración de seguridad de Cookies 
require_once '../config/session_start.php';
require_once '../models/Usuario.php'; // Usar Modelo

$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$telefono = $_POST['telefono'] ?? null;

if (empty($nombre) || empty($email) || empty($password)) {
    die("Faltan datos.");
}

// Encriptar
$hash = password_hash($password, PASSWORD_DEFAULT);

// Usar el Modelo para crear
// (Asegurate que tu modelo Usuario::crear verifique si existe el email primero)
$usuario_id = Usuario::crear($nombre, $email, $hash, $telefono);

if ($usuario_id) {
    $_SESSION['usuario_id'] = $usuario_id;
    $_SESSION['email'] = $email;
    $_SESSION['nombre'] = $nombre;
    $_SESSION['rol'] = 'Cliente'; 
    
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    
    // IMPORTANTE: Redirigir al CONTROLADOR de cliente, no a la vista directa
    header("Location: ClienteController.php"); 
    exit;
} else {
    echo "<script>alert('El email ya existe o hubo un error'); window.location.href='../views/registrar.html';</script>";
}
?>