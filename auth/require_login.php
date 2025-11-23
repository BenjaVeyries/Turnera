<?php

require_once '../config/session_start.php';

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