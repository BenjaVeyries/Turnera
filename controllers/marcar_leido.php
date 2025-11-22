<?php
// controllers/marcar_leido.php
session_start();
require_once '../auth/require_login.php'; // Tu seguridad
require_once '../models/Notificacion.php';

// Marcar todas como leídas
Notificacion::marcarTodasLeidas($_SESSION['usuario_id']);

// Volver a donde estaba (Referer) o al dashboard por defecto
if(isset($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    // Fallback si no hay referer
    if($_SESSION['rol'] === 'Administrador') {
        header("Location: AdminDashboard.php");
    } else {
        header("Location: ClienteController.php");
    }
}
exit;
?>