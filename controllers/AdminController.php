<?php

// Configuración de seguridad de Cookies 
ini_set('session.cookie_httponly', 1); // JS no puede leer la cookie
ini_set('session.use_only_cookies', 1); // Forzar uso de cookies

session_start();
require_once '../models/Turno.php';
require_once '../models/Notificacion.php';

if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
    echo json_encode(['status'=>'error', 'message'=>'Error de seguridad']);
    exit;
}
// Verificar Admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Administrador') {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $estado = $_POST['estado'] ?? null;

    if ($id && $estado) {
        // USAR MODELO
        if(Turno::cambiarEstado($id, $estado)) {
            $stmt = $pdo->prepare("SELECT usuario_id, fecha, hora FROM turnos WHERE id = ?");
            $stmt->execute([$id]);
            $turno = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($turno) {
                $mensaje = "Tu turno del " . $turno['fecha'] . " a las " . $turno['hora'] . " ha sido " . strtoupper($estado) . ".";
                Notificacion::crear($turno['usuario_id'], $mensaje);
            }

            echo json_encode(['status' => 'ok']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error DB']);
        }
    }
}
?>