<?php
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