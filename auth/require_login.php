<?php

// Configuración de seguridad de Cookies 
ini_set('session.cookie_httponly', 1); // JS no puede leer la cookie
ini_set('session.use_only_cookies', 1); // Forzar uso de cookies

// auth/require_login.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Verificar si está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}

// 2. Seguridad extra (Anti-Session Hijacking)
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}
?>