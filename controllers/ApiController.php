<?php
// controllers/ApiController.php

// 1. Configuración de Cookies
require_once '../config/session_start.php';

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
        // SOLO SUS TURNOS (Aquí la columna se llama 'nombre' directo de la tabla usuarios)
        $sql = "SELECT t.id, t.fecha, t.hora, t.estado, u.nombre 
                FROM turnos t 
                JOIN usuarios u ON t.usuario_id = u.id 
                WHERE t.estado != 'cancelado' AND t.estado != 'cancelado_cliente' AND t.peluquero_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION['usuario_id']]); 
    } else {
        // ADMIN VE TODO (Aquí renombraste 'u.nombre' como 'cliente')
       $sql = "SELECT t.id, t.fecha, t.hora, t.estado, u.nombre as cliente, p.nombre as peluquero
                FROM turnos t 
                JOIN usuarios u ON t.usuario_id = u.id 
                LEFT JOIN usuarios p ON t.peluquero_id = p.id 
                WHERE t.estado != 'cancelado' AND t.estado != 'cancelado_cliente'";
        $stmt = $pdo->query($sql);
    }
    
    $turnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $eventos = [];
    foreach($turnos as $t) {
        // Definir color
        $color = '#ca8a04'; // Amarillo (Pendiente)
        if ($t['estado'] == 'confirmado') $color = '#16a34a'; // Verde
        
        // [CORRECCIÓN IMPORTANTE]
        // Detectamos si el campo viene como 'cliente' (Admin) o 'nombre' (Peluquero)
        $nombreCliente = $t['cliente'] ?? $t['nombre'];
        
        $eventos[] = [
            'title' => substr($t['hora'], 0, 5) . ' - ' . $nombreCliente,
            'start' => $t['fecha'] . 'T' . $t['hora'],
            'color' => $color,
            'extendedProps' => [
                'cliente'   => $nombreCliente,
                'peluquero' => $t['peluquero'] ?? 'Mí mismo',
                'estado'    => ucfirst($t['estado'])
            ]
        ];
    }
    
    echo json_encode($eventos);

} catch (Exception $e) {
    echo json_encode([]);
}
?>