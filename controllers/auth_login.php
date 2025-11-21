<?php
session_start();
require_once '../models/Usuario.php'; // Importamos Modelo

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    die("Faltan datos.");
}

// Usamos el modelo
$usuario = Usuario::buscarPorEmail($email);

if ($usuario && password_verify($password, $usuario['password_hash'])) {
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['email'] = $usuario['email'];
    $_SESSION['nombre'] = $usuario['nombre'];
    $_SESSION['rol'] = $usuario['rol'];

    // Redirecciones correctas
    if ($usuario['rol'] === 'Administrador') {
        header("Location: AdminDashboard.php"); // Va al controlador
    } else {
        header("Location: ../views/cliente_turnos.php");
    }
    exit;
} else {
    // (Aquí mantienes tu lógica de SweetAlert o redirección a error)
    echo "<script>alert('Datos incorrectos'); window.location.href='../views/login.html';</script>";
    exit;
}
?>