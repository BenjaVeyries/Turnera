<?php
require_once __DIR__ . '/../config/conexion_db.php';

class Notificacion {
    
    // Crear una notificación para un usuario especifico
    public static function crear($usuario_id, $mensaje) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO notificaciones (usuario_id, mensaje) VALUES (?, ?)");
        return $stmt->execute([$usuario_id, $mensaje]);
    }

    // Crear notificación para TODOS los admins (cuando un cliente reserva)
    public static function notificarAdmins($mensaje) {
        global $pdo;
        // Buscamos todos los admins
        $stmt = $pdo->query("SELECT id FROM usuarios WHERE rol = 'Administrador'");
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql = "INSERT INTO notificaciones (usuario_id, mensaje) VALUES (?, ?)";
        $stmtInsert = $pdo->prepare($sql);

        foreach ($admins as $admin) {
            $stmtInsert->execute([$admin['id'], $mensaje]);
        }
    }

    // Obtener no leídas de un usuario
    public static function obtenerNoLeidas($usuario_id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM notificaciones WHERE usuario_id = ? AND leido = 0 ORDER BY creado_en DESC");
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Marcar todas como leídas (lo usaremos al abrir el panel o con un botón)
    public static function marcarTodasLeidas($usuario_id) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE notificaciones SET leido = 1 WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);
    }
}
?>