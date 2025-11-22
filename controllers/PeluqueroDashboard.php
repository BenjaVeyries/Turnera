<?php
require_once '../auth/require_login.php';
require_once '../models/Turno.php';

// Seguridad: Solo Peluqueros
if ($_SESSION['rol'] !== 'Peluquero') {
    header("Location: ../controllers/auth_login.php");
    exit;
}

// Cargar SOLO los turnos de este peluquero
$turnos = Turno::obtenerPorPeluquero($_SESSION['usuario_id']);

require '../views/peluquero_dashboard.php'; 
?>