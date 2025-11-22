<?php
// controllers/AdminDashboard.php
require_once '../auth/require_login.php';
require_once '../models/Turno.php';
require_once '../models/Notificacion.php'; // <--- [IMPORTANTE] Faltaba esto

// Seguridad: Solo Admin
if ($_SESSION['rol'] !== 'Administrador') {
    header("Location: ClienteController.php");
    exit;
}

// 1. Cargar Turnos
$turnos = Turno::obtenerTodos();

// 2. Cargar Notificaciones (Esto es lo que te faltaba)
$notificaciones = Notificacion::obtenerNoLeidas($_SESSION['usuario_id']);

// 3. Cargar Vista
require '../views/admin_dashboard.php'; 
?>