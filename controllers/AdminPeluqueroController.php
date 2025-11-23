<?php
// controllers/AdminPeluqueroController.php
require_once '../config/session_start.php';
require_once '../models/Usuario.php';
require_once '../models/Peluquero.php';

// 1. Seguridad de Sesión
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') {
    die("Acceso denegado");
}

// 2. Seguridad CSRF (Validamos el token que agregaste en el form)
if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    die("Error de seguridad: Token inválido");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telefono = $_POST['telefono'] ?? '';
    $bio = $_POST['bio'] ?? '';
    
    // --- PROCESAR FOTO CON SEGURIDAD ---
    $nombreFoto = null;
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        
        //  Validación de Seguridad MIME TYPE (Lo que preguntabas)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['foto']['tmp_name']);
        finfo_close($finfo); // Cerramos el recurso

        // Lista blanca de tipos permitidos
        $formatosPermitidos = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!in_array($mime, $formatosPermitidos)) {
            // Si no es imagen real, cortamos todo
            die("Error: El archivo no es una imagen válida (Solo JPG o PNG).");
        }
        // ---------------------------------------------------------

        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nombreFoto = 'pro_' . uniqid() . '.' . $ext;
        $destino = '../public/uploads/' . $nombreFoto;
        
        move_uploaded_file($_FILES['foto']['tmp_name'], $destino);
    }

    // 3. CREAR PELUQUERO
    $peluquero_id = Usuario::crearPeluquero($nombre, $email, $password, $telefono, $bio, $nombreFoto);

    if ($peluquero_id) {
        // ... (tu lógica de guardar servicios y horarios sigue igual) ...
        if (isset($_POST['servicios']) && is_array($_POST['servicios'])) {
            foreach ($_POST['servicios'] as $servicio_id) {
                Peluquero::asignarServicio($peluquero_id, $servicio_id);
            }
        }

        if (isset($_POST['dias_activos']) && is_array($_POST['dias_activos'])) {
            foreach ($_POST['dias_activos'] as $dia => $valor) {
                if (isset($_POST['horarios'][$dia]) && is_array($_POST['horarios'][$dia])) {
                    foreach ($_POST['horarios'][$dia] as $turno) {
                        $inicio = $turno['inicio'];
                        $fin = $turno['fin'];
                        if (!empty($inicio) && !empty($fin)) {
                            Peluquero::guardarHorario($peluquero_id, $dia, $inicio, $fin);
                        }
                    }
                }
            }
        }

        echo "<script>alert('Profesional configurado con éxito'); window.location.href='AdminDashboard.php';</script>";
    } else {
        echo "<script>alert('Error al crear usuario (¿Email repetido?)'); window.history.back();</script>";
    }
}
?>