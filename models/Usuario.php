<?php
require_once __DIR__ . '/../config/conexion_db.php';

class Usuario {
    
    public static function buscarPorEmail($email) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT id, nombre, rol, email, password_hash FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function crear($nombre, $email, $hash) {
        global $pdo;
        // Verificar si existe
        if(self::buscarPorEmail($email)) return false;

        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES (?, ?, ?, 'Cliente')");
        $stmt->execute([$nombre, $email, $hash]);
        return $pdo->lastInsertId();
    }
}
?>