<?php

// Configuración de seguridad de Cookies 
ini_set('session.cookie_httponly', 1); // JS no puede leer la cookie
ini_set('session.use_only_cookies', 1); // Forzar uso de cookies

session_start();
require_once '../models/Turno.php';
require_once '../models/Notificacion.php';
require_once '../config/conexion_db.php';
require_once '../models/Usuario.php';

if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
    echo json_encode(['status'=>'error', 'message'=>'Error de seguridad']);
    exit;
}
if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] !== 'Administrador' && $_SESSION['rol'] !== 'Peluquero')) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $estado = $_POST['estado'] ?? null;

    if ($id && $estado) {
        if(Turno::cambiarEstado($id, $estado)) {
            
            // 1. Buscamos datos del turno y del cliente (incluyendo teléfono)
            $stmt = $pdo->prepare("
                SELECT t.usuario_id, t.fecha, t.hora, u.nombre, u.telefono 
                FROM turnos t 
                JOIN usuarios u ON t.usuario_id = u.id 
                WHERE t.id = ?
            ");
            $stmt->execute([$id]);
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);

            // 2. Notificación Interna
            if ($datos) {
                $mensaje = "Tu turno del " . $datos['fecha'] . " a las " . substr($datos['hora'], 0, 5) . " ha sido " . strtoupper($estado) . ".";
                Notificacion::crear($datos['usuario_id'], $mensaje);
            }

            // 3. Generar Link de WhatsApp
            $waLink = null;
            if ($datos && !empty($datos['telefono'])) {
                // Limpiar número
                $tel = preg_replace('/[^0-9]/', '', $datos['telefono']);
                
                // Emoji según estado
                $emoji = ($estado == 'confirmado') ? '✅' : '❌';
                $texto = "Hola {$datos['nombre']}! $emoji Tu turno del {$datos['fecha']} a las " . substr($datos['hora'], 0, 5) . " ha sido " . strtoupper($estado) . ".";
                
                $waLink = "https://wa.me/$tel?text=" . urlencode($texto);
            }

            echo json_encode(['status' => 'ok', 'wa_link' => $waLink]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al cambiar estado']);
        }
    }
}
?>