<?php
// Configuración de seguridad de Cookies 
ini_set('session.cookie_httponly', 1); // JS no puede leer la cookie
ini_set('session.use_only_cookies', 1); // Forzar uso de cookies

session_start();
session_destroy();
// Corregimos la ruta: Salimos de controllers (..) y entramos a views
echo "<script>sessionStorage.removeItem('usuario_id'); window.location.href='../views/login.html';</script>";
exit;
?>