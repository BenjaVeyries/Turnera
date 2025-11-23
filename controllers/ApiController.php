<?php
// controllers/ApiController.php

// 1. Configuración de Cookies
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

session_start();

header('Content-Type: application/json');
require_once '../config/conexion_db.php';

// Seguridad Dual
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'Administrador' && $_SESSION['rol'] !== 'Peluquero')) {
    echo json_encode([]); 
    exit;
}

try {
    global $pdo;
    
    // Lógica diferenciada
    if ($_SESSION['rol'] === 'Peluquero') {
        // SOLO SUS TURNOS
        $sql = "SELECT t.id, t.fecha, t.hora, t.estado, u.nombre 
                FROM turnos t 
                JOIN usuarios u ON t.usuario_id = u.id 
                WHERE t.estado != 'cancelado' AND t.estado != 'cancelado_cliente' AND t.peluquero_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION['usuario_id']]); // ID del peluquero logueado
    } else {
        // ADMIN VE TODO
        $sql = "SELECT t.id, t.fecha, t.hora, t.estado, u.nombre 
                FROM turnos t 
                JOIN usuarios u ON t.usuario_id = u.id 
                WHERE t.estado != 'cancelado' AND t.estado != 'cancelado_cliente'";
        $stmt = $pdo->query($sql);
    }
    
    $turnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // --- BUCLE QUE FALTABA ---
    $eventos = [];
    foreach($turnos as $t) {
        // Definir color
        $color = '#ca8a04'; // Amarillo (Pendiente)
        if ($t['estado'] == 'confirmado') $color = '#16a34a'; // Verde
        
        $eventos[] = [
            'title' => substr($t['hora'], 0, 5) . ' - ' . $t['nombre'],
            'start' => $t['fecha'] . 'T' . $t['hora'],
            'color' => $color
        ];
    }
    
    echo json_encode($eventos);

} catch (Exception $e) {
    echo json_encode([]);
}
?>