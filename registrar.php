<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=barberia;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

// Obtener datos del formulario
$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($nombre) || empty($email) || empty($password)) {
    die("Faltan datos.");
}

// Verificar si ya existe el email
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire('Error','El email ya está registrado','error').then(()=>{
            window.location='registrar.html';
        });
    </script>";
    exit;
}

// Hash seguro de la contraseña
$hash = password_hash($password, PASSWORD_DEFAULT);

// Insertar usuario en la base de datos
$stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password_hash) VALUES (?, ?, ?)");
$stmt->execute([$nombre, $email, $hash]);

// Guardar sesión automáticamente
$usuario_id = $pdo->lastInsertId();
$_SESSION['usuario_id'] = $usuario_id;
$_SESSION['email'] = $email;
$_SESSION['nombre'] = $nombre;

// Redirigir a turnos.php
header("Location: turnos.php");
exit;



