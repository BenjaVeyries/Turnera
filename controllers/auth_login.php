<?php
// Configuración de seguridad de Cookies 
ini_set('session.cookie_httponly', 1); // JS no puede leer la cookie
ini_set('session.use_only_cookies', 1); // Forzar uso de cookies

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

    // GENERAR TOKEN CSRF
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    // Redirecciones correctas
    if ($usuario['rol'] === 'Administrador') {
        header("Location: AdminDashboard.php"); // Va al controlador
    } else {
       // Redirigimos al CONTROLADOR, que se encarga de buscar los datos y cargar la vista
        header("Location: ClienteController.php");
    }
    exit;
} else {
    // (Aquí mantienes tu lógica de SweetAlert o redirección a error)
    echo "<script>alert('Datos incorrectos'); window.location.href='../views/login.html';</script>";
    exit;
}
?>