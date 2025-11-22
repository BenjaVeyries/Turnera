<?php
require_once __DIR__ . '/../config/conexion_db.php';

class Peluquero {
    
    // Traer solo los usuarios que son "Peluquero"
    public static function obtenerTodos() {
        global $pdo;
        $sql = "SELECT id, nombre, email, foto, biografia FROM usuarios WHERE rol = 'Peluquero'";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Traer peluqueros que hagan un servicio específico (Filtro Clave para el Paso 2 del Cliente)
    public static function obtenerPorServicio($servicio_id) {
        global $pdo;
        // [IMPORTANTE] Asegurate de que 'u.foto' esté en el SELECT
        $sql = "SELECT u.id, u.nombre, u.foto, u.biografia 
                FROM usuarios u
                JOIN peluquero_servicios ps ON u.id = ps.peluquero_id
                WHERE u.rol = 'Peluquero' AND ps.servicio_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$servicio_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Asignar un servicio a un peluquero
    public static function asignarServicio($peluquero_id, $servicio_id) {
        global $pdo;
        // Evitamos duplicados con IGNORE
        $stmt = $pdo->prepare("INSERT IGNORE INTO peluquero_servicios (peluquero_id, servicio_id) VALUES (?, ?)");
        return $stmt->execute([$peluquero_id, $servicio_id]);
    }

    // Guardar Horario (Admin)
    public static function guardarHorario($peluquero_id, $dia, $inicio, $fin) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO disponibilidad_peluquero (peluquero_id, dia_semana, hora_inicio, hora_fin) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$peluquero_id, $dia, $inicio, $fin]);
    }

    // Obtener disponibilidad horaria de un peluquero para un día específico (Ej: Lunes)
    public static function obtenerHorarios($peluquero_id, $dia_semana) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT hora_inicio, hora_fin FROM disponibilidad_peluquero WHERE peluquero_id = ? AND dia_semana = ?");
        $stmt->execute([$peluquero_id, $dia_semana]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>