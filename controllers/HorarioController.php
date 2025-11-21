<?php

// Configuración de seguridad de Cookies 
ini_set('session.cookie_httponly', 1); // JS no puede leer la cookie
ini_set('session.use_only_cookies', 1); // Forzar uso de cookies


session_start();
require_once '../models/Turno.php'; 

if(!isset($_SESSION['usuario_id'])){
    http_response_code(403);
    exit;
}

$fecha = $_GET['fecha'] ?? '';
if(!$fecha){
    echo json_encode([]);
    exit;
}

try {
    // Generar horas totales
    $inicio = new DateTime('09:00');
    $fin = new DateTime('18:00');
    $horas_disponibles = [];
    while($inicio <= $fin){
        $horas_disponibles[] = $inicio->format('H:i:s');
        $inicio->modify('+30 minutes');
    }

    // Consultar ocupadas (Puede fallar si falta la columna 'estado')
    $ocupadas = Turno::obtenerOcupadas($fecha);

    $libres = array_diff($horas_disponibles, $ocupadas);
    echo json_encode(array_values($libres));

} catch (Exception $e) {
    // En caso de error, devolvemos array vacío para no romper el JS
    error_log("Error en HorarioController: " . $e->getMessage());
    echo json_encode([]);
}
?>