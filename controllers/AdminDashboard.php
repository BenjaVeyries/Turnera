<?php
require_once '../auth/require_login.php';
require_once '../config/db.php';

// Lógica: Consultar turnos
$sql = "SELECT ... FROM turnos ...";
$stmt = $pdo->query($sql);
$turnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cargar la vista
require '../views/admin_dashboard.php'; 
?>