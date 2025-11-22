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

    // models/Usuario.php

    // 1. Verificar si el usuario está bloqueado temporalmente
    public static function estaBloqueado($email) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT bloqueado_hasta FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['bloqueado_hasta']) {
            $ahora = date('Y-m-d H:i:s');
            if ($user['bloqueado_hasta'] > $ahora) {
                return true; // Sigue bloqueado
            }
        }
        return false; // No está bloqueado o ya pasó el tiempo
    }

    // 2. Registrar un intento fallido
    public static function registrarFallo($email) {
        global $pdo;
        // Buscamos intentos actuales
        $stmt = $pdo->prepare("SELECT intentos_fallidos FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) return; // Si el email no existe, no hacemos nada (seguridad)

        $intentos = $user['intentos_fallidos'] + 1;

        if ($intentos >= 5) {
            // Si llegó a 5, bloqueamos por 10 minutos
            $bloqueado_hasta = date('Y-m-d H:i:s', strtotime('+10 minutes'));
            $stmt = $pdo->prepare("UPDATE usuarios SET intentos_fallidos = 0, bloqueado_hasta = ? WHERE email = ?");
            $stmt->execute([$bloqueado_hasta, $email]);
        } else {
            // Solo sumamos uno
            $stmt = $pdo->prepare("UPDATE usuarios SET intentos_fallidos = ? WHERE email = ?");
            $stmt->execute([$intentos, $email]);
        }
    }

    // 3. Limpiar fallos (cuando logra entrar)
    public static function limpiarFallos($email) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE usuarios SET intentos_fallidos = 0, bloqueado_hasta = NULL WHERE email = ?");
        $stmt->execute([$email]);
    }
}
?>