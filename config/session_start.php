<?php
// config/session_start.php

// Configuración de seguridad de Cookies
// (Solo si la sesión no ha iniciado aún)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    // ini_set('session.cookie_secure', 1); // Descomentar si usas HTTPS
    session_start();
}
?>