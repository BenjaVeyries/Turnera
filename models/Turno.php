<?php
require_once __DIR__ . '/../config/conexion_db.php';

class Turno {
    
    // 1. Obtener todos los turnos (Para el Admin)
    public static function obtenerTodos() {
        global $pdo;
        $sql = "SELECT t.id, t.fecha, t.hora, t.estado, u.nombre 
                FROM turnos t 
                JOIN usuarios u ON t.usuario_id = u.id 
                ORDER BY t.fecha DESC, t.hora ASC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Obtener turnos de un usuario especifico (Para el Cliente)
    public static function obtenerPorUsuario($usuario_id) {
        global $pdo;
        // Agregamos 'id' al principio
        $stmt = $pdo->prepare("SELECT id, fecha, hora, estado FROM turnos WHERE usuario_id=? ORDER BY fecha, hora");
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Crear un turno nuevo
    public static function crear($usuario_id, $fecha, $hora) {
        global $pdo;
        
        // Verificar duplicados
        $stmt = $pdo->prepare("SELECT id FROM turnos WHERE fecha=? AND hora=? AND estado != 'cancelado'");
        $stmt->execute([$fecha, $hora]);
        if($stmt->fetch()) {
            return false; // Ya existe
        }

        $stmt = $pdo->prepare("INSERT INTO turnos(usuario_id, fecha, hora, estado) VALUES(?,?,?, 'pendiente')");
        return $stmt->execute([$usuario_id, $fecha, $hora]);
    }

    // 4. Cambiar estado (Aceptar/Cancelar)
    public static function cambiarEstado($id, $estado) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE turnos SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id]);
    }
    
    // 5. Obtener horas ocupadas (Para el filtro de horas)
    public static function obtenerOcupadas($fecha) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT hora FROM turnos WHERE fecha = ? AND estado != 'cancelado'");
        $stmt->execute([$fecha]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // 6. Cancelar turno por el cliente (Seguridad: verificamos usuario_id)
    public static function cancelarPorCliente($idTurno, $idUsuario) {
        global $pdo;
        // Solo permitimos cancelar si no está ya cancelado
        $stmt = $pdo->prepare("UPDATE turnos SET estado = 'cancelado_cliente' WHERE id = ? AND usuario_id = ? AND estado != 'cancelado_cliente'");
        $stmt->execute([$idTurno, $idUsuario]);
        return $stmt->rowCount() > 0; // Devuelve true si se modificó algo
    }
}
?>