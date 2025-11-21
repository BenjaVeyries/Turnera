<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=barberia;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error conexión DB: " . $e->getMessage());
}

// Obtener datos del formulario
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    die("Faltan datos.");
}

// Buscar usuario por email
$stmt = $pdo->prepare("SELECT id, nombre, rol, email, password_hash FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario && password_verify($password, $usuario['password_hash'])) {
    // Guardar datos en sesión
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['email'] = $usuario['email'];
    $_SESSION['nombre'] = $usuario['nombre']; // <-- Guardamos el nombre
    $_SESSION['rol'] = $usuario['rol'];


    if ($usuario['rol'] === 'Administrador') {
        header("Location: panelAdmin.php");
    } else {
        // Redirigir a la página de turnos
        header("Location: turnos.php");
    }
    
    
    exit;
} else {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire('Error','Email o contraseña incorrectos','error').then(()=>{
            window.location='login.html';
        });
    </script>";
    exit;
}





