<?php
require_once __DIR__ . '/../config/conexion_db.php';

class Configuracion {
    
    // Obtener un valor por su clave
    public static function obtener($clave) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT valor FROM configuracion WHERE clave = ?");
        $stmt->execute([$clave]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $res['valor'] : '';
    }

    // Obtener todas las configuraciones (para el panel admin)
    public static function obtenerTodo() {
        global $pdo;
        $stmt = $pdo->query("SELECT clave, valor FROM configuracion");
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Devuelve array [clave => valor]
    }

    // Guardar o actualizar una configuración
    public static function guardar($clave, $valor) {
        global $pdo;
        $sql = "INSERT INTO configuracion (clave, valor) VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE valor = VALUES(valor)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$clave, $valor]);
    }
}
?>