<?php
session_start();
session_destroy();
// Corregimos la ruta: Salimos de controllers (..) y entramos a views
echo "<script>sessionStorage.removeItem('usuario_id'); window.location.href='../views/login.html';</script>";
exit;
?>