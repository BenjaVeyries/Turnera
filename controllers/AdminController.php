<?php
// admin_acciones.php
session_start();

// 1. Seguridad
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: login.html");
    exit;
}


header('Content-Type: application/json');

// Seguridad básica: Verificar si está logueado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $estado = $_POST['estado'] ?? null;

    if ($id && $estado) {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=barberia;charset=utf8", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Actualizamos el estado del turno
            $stmt = $pdo->prepare("UPDATE turnos SET estado = ? WHERE id = ?");
            $stmt->execute([$estado, $id]);

            echo json_encode(['status' => 'ok']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error DB']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    }
}
?>