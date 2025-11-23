<?php
// controllers/HorarioController.php

// Configuración de seguridad de Cookies 

require_once '../config/session_start.php';
header('Content-Type: application/json');
require_once '../config/conexion_db.php';
require_once '../models/Peluquero.php'; // Necesitamos el modelo Peluquero

// Validar sesión
if(!isset($_SESSION['usuario_id'])){
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$fecha = $_GET['fecha'] ?? '';
$peluquero_id = $_GET['peluquero_id'] ?? '';

if(!$fecha || !$peluquero_id){
    echo json_encode([]); // Si faltan datos, no mostramos nada
    exit;
}

try {
    // 1. Descubrir qué día de la semana es (Traducción Inglés -> Español)
    $diasIngles = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
    $diasEspanol = ['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'];
    $diaSemanaIngles = date('l', strtotime($fecha)); 
    $diaSemana = str_replace($diasIngles, $diasEspanol, $diaSemanaIngles);

    // 2. Buscar el Rango Horario de ESTE peluquero para ESE día
    // Usamos la función que agregaste en el Paso 3
    $rangos = Peluquero::obtenerHorarios($peluquero_id, $diaSemana);

    $horasPosibles = [];

    if (empty($rangos)) {
        // Si no tiene horario configurado ese día, devolvemos vacío (No trabaja)
        echo json_encode([]); 
        exit;
    }

    // 3. Generar los intervalos permitidos (Ej: de 17:00 a 20:00)
    foreach ($rangos as $rango) {
        $inicio = new DateTime($rango['hora_inicio']);
        $fin = new DateTime($rango['hora_fin']);
        
        // Generamos turnos cada 30 min hasta llegar al horario de salida
        while ($inicio < $fin) { 
            $horasPosibles[] = $inicio->format('H:i:s');
            $inicio->modify('+30 minutes'); 
        }
    }

    // 4. Buscar qué turnos YA tiene ocupados ese peluquero ese día
    // NOTA: Aquí filtramos por peluquero_id para que no choquen con otros
    global $pdo;
    $stmt = $pdo->prepare("SELECT hora FROM turnos WHERE fecha = ? AND peluquero_id = ? AND estado != 'cancelado' AND estado != 'cancelado_cliente'");
    $stmt->execute([$fecha, $peluquero_id]);
    $ocupadas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // 5. Restar (Disponibles - Ocupadas)
    $finales = array_diff($horasPosibles, $ocupadas);

    echo json_encode(array_values($finales));

} catch (Exception $e) {
    echo json_encode([]);
}
?>