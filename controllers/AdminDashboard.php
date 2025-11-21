<?php
require_once '../auth/require_login.php';
require_once '../models/Turno.php'; // Importamos el Modelo

// Usamos el modelo para pedir los datos
$turnos = Turno::obtenerTodos();

// Cargamos la vista
require '../views/admin_dashboard.php'; 
?>