<?php
require_once '../auth/require_login.php';
require_once '../models/Turno.php'; // Importamos el Modelo
require_once '../models/Notificacion.php';

$notificaciones = Notificacion::obtenerNoLeidas($_SESSION['usuario_id']);

if ($_SESSION['rol'] !== 'Administrador') {
    // Si es cliente y quiere entrar acá, lo mandamos a su panel
    header("Location: ClienteController.php");
    exit;
}
// Usamos el modelo para pedir los datos
$turnos = Turno::obtenerTodos();

// Cargamos la vista
require '../views/admin_dashboard.php'; 
?>