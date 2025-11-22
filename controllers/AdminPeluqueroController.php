<?php
// controllers/AdminPeluqueroController.php
session_start();
require_once '../models/Usuario.php';
require_once '../models/Peluquero.php'; // Necesitamos este modelo nuevo

// Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') {
    die("Acceso denegado");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. DATOS BÁSICOS
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telefono = $_POST['telefono'] ?? '';
    $bio = $_POST['bio'] ?? '';
    
    // 2. PROCESAR FOTO
    $nombreFoto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nombreFoto = 'pro_' . uniqid() . '.' . $ext;
        $destino = '../public/uploads/' . $nombreFoto;
        move_uploaded_file($_FILES['foto']['tmp_name'], $destino);
    }

    // 3. CREAR USUARIO (PELUQUERO)
    // Usuario::crearPeluquero devuelve el ID insertado o false
    // (Asegurate que tu modelo Usuario.php devuelva el ID con $pdo->lastInsertId())
    $peluquero_id = Usuario::crearPeluquero($nombre, $email, $password, $telefono, $bio, $nombreFoto);

    if ($peluquero_id) {
        
        // 4. GUARDAR SERVICIOS (Si seleccionó alguno)
        if (isset($_POST['servicios']) && is_array($_POST['servicios'])) {
            foreach ($_POST['servicios'] as $servicio_id) {
                Peluquero::asignarServicio($peluquero_id, $servicio_id);
            }
        }

       
        // 5. GUARDAR HORARIOS (SOPORTE DOBLE TURNO)
        if (isset($_POST['dias_activos']) && is_array($_POST['dias_activos'])) {
            
            foreach ($_POST['dias_activos'] as $dia => $valor) {
                // Verificamos si existen horarios enviados para este día
                if (isset($_POST['horarios'][$dia]) && is_array($_POST['horarios'][$dia])) {
                    
                    // Recorremos los turnos (0 y 1)
                    foreach ($_POST['horarios'][$dia] as $turno) {
                        $inicio = $turno['inicio'];
                        $fin = $turno['fin'];

                        // Solo guardamos si AMBOS campos tienen hora (para evitar guardar vacíos)
                        if (!empty($inicio) && !empty($fin)) {
                            Peluquero::guardarHorario($peluquero_id, $dia, $inicio, $fin);
                        }
                    }
                }
            }
        }

        // Éxito
        echo "<script>alert('Profesional configurado con éxito'); window.location.href='AdminDashboard.php';</script>";
    } else {
        echo "<script>alert('Error al crear usuario (¿Email repetido?)'); window.history.back();</script>";
    }
}
?>