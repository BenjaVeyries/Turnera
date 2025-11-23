<?php
// controllers/PeluqueroDashboard.php
require_once '../auth/require_login.php';
require_once '../models/Turno.php';
require_once '../models/Notificacion.php'; // 1. Importar modelo

// Seguridad...

// Cargar turnos...
$turnos = Turno::obtenerPorPeluquero($_SESSION['usuario_id']);

// 2. CARGAR NOTIFICACIONES
$notificaciones = Notificacion::obtenerNoLeidas($_SESSION['usuario_id']);

require '../views/peluquero_dashboard.php'; 
?>