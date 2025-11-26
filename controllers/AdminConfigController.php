<?php
require_once '../config/session_start.php';
require_once '../auth/require_login.php';
require_once '../models/Configuracion.php';

if ($_SESSION['rol'] !== 'Administrador') die("Acceso denegado");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar CSRF
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        die("Error de token");
    }

    // Guardar los campos que vengan
    if (isset($_POST['mapa_url'])) Configuracion::guardar('mapa_url', $_POST['mapa_url']);
    if (isset($_POST['direccion'])) Configuracion::guardar('direccion', $_POST['direccion']);
    if (isset($_POST['telefono'])) Configuracion::guardar('telefono', $_POST['telefono']);

    header("Location: ../controllers/AdminDashboard.php?status=config_ok");
    exit;
}
?>