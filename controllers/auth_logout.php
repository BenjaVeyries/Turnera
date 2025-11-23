<?php
// Configuración de seguridad de Cookies 
require_once '../config/session_start.php';
session_destroy();
// Corregimos la ruta: Salimos de controllers (..) y entramos a views
echo "<script>sessionStorage.removeItem('usuario_id'); window.location.href='../views/login.html';</script>";
exit;
?>