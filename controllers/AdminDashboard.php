<?php
// controllers/AdminDashboard.php
require_once '../config/session_start.php';
require_once '../auth/require_login.php';
require_once '../models/Turno.php';
require_once '../models/Notificacion.php'; 

// Seguridad: Solo Admin
if ($_SESSION['rol'] !== 'Administrador') {
    header("Location: ClienteController.php");
    exit;
}

// 1. Cargar Turnos
$turnos = Turno::obtenerTodos();

// 2. Cargar Notificaciones 
$notificaciones = Notificacion::obtenerNoLeidas($_SESSION['usuario_id']);

// 3. Cargar Vista
require '../views/admin_dashboard.php'; 
?>