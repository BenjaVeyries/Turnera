<?php
// controllers/auth_login.php

// Configuración de Cookies
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

session_start();
require_once '../models/Usuario.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    die("Faltan datos.");
}

// 1. VERIFICAR BLOQUEO (Rate Limit)
if (Usuario::estaBloqueado($email)) {
    header("Location: ../views/login.html?error=blocked");
    exit;
}

// 2. Buscar usuario
$usuario = Usuario::buscarPorEmail($email);

// 3. Verificar contraseña
if ($usuario && password_verify($password, $usuario['password_hash'])) {
    
    // ¡ÉXITO! -> Limpiamos los fallos previos
    Usuario::limpiarFallos($email);

    // ... (Lógica de sesión normal) ...
    session_regenerate_id(true);
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['email'] = $usuario['email'];
    $_SESSION['nombre'] = $usuario['nombre'];
    $_SESSION['rol'] = $usuario['rol'];

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    if ($usuario['rol'] === 'Administrador') {
        header("Location: AdminDashboard.php");
    } else {
        header("Location: ClienteController.php");
    }
    exit;

} else {
    // ¡ERROR! -> Registramos el fallo
    // Solo registramos si el usuario existe (para no dar pistas de emails válidos, 
    // aunque nuestra función registrarFallo ya maneja eso internamente)
    if ($usuario) {
        Usuario::registrarFallo($email);
    }

    header("Location: ../views/login.html?error=invalid");
}
?>