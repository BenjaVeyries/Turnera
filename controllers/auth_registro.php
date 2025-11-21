<?php
session_start();
require_once '../models/Usuario.php'; // Usar Modelo

$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($nombre) || empty($email) || empty($password)) {
    die("Faltan datos.");
}

// Encriptar
$hash = password_hash($password, PASSWORD_DEFAULT);

// Usar el Modelo para crear
// (Asegurate que tu modelo Usuario::crear verifique si existe el email primero)
$usuario_id = Usuario::crear($nombre, $email, $hash);

if ($usuario_id) {
    $_SESSION['usuario_id'] = $usuario_id;
    $_SESSION['email'] = $email;
    $_SESSION['nombre'] = $nombre;
    $_SESSION['rol'] = 'Cliente'; 
    
    // IMPORTANTE: Redirigir al CONTROLADOR de cliente, no a la vista directa
    header("Location: ClienteController.php"); 
    exit;
} else {
    echo "<script>alert('El email ya existe o hubo un error'); window.location.href='../views/registrar.html';</script>";
}
?>