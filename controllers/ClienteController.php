<?php
require_once '../auth/require_login.php'; // Maneja la sesión y seguridad
require_once '../models/Turno.php';

// 1. Obtener datos usando el Modelo
$turnos = Turno::obtenerPorUsuario($_SESSION['usuario_id']);
$nombre = $_SESSION['nombre'];

// 2. Cargar la Vista
require '../views/cliente_turnos.php';
?>