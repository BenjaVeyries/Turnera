<?php

// 1. Configuración de Cookies (Igual que en los otros)
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

session_start();

header('Content-Type: application/json');
require_once '../config/conexion_db.php'; // Usamos la conexión centralizada

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') {
    echo json_encode([]); 
    exit;
}

try {
    global $pdo; // Aseguramos usar la variable del config
    // Traemos solo confirmados y pendientes (ignoramos cancelados para no ensuciar el calendario)
    $sql = "SELECT t.id, t.fecha, t.hora, t.estado, u.nombre 
            FROM turnos t 
            JOIN usuarios u ON t.usuario_id = u.id 
            WHERE t.estado != 'cancelado'"; 
            
    $stmt = $pdo->query($sql);
    $turnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $eventos = [];
    
    foreach($turnos as $t) {
        // Color: Verde si está confirmado, Amarillo oscuro si está pendiente
        $color = ($t['estado'] == 'confirmado') ? '#16a34a' : '#ca8a04'; 
        
        $eventos[] = [
            'title' => $t['hora'] . ' - ' . $t['nombre'], // Ej: "14:00 - Juan"
            'start' => $t['fecha'] . 'T' . $t['hora'],    // Formato ISO necesario
            'color' => $color
            // Puedes agregar más datos si quieres
        ];
    }
    
    echo json_encode($eventos);

} catch (Exception $e) {
    echo json_encode([]);
}
?>