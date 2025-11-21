<?php
session_start();
if(!isset($_SESSION['usuario_id'])){
    http_response_code(403);
    exit;
}

$fecha = $_GET['fecha'] ?? '';
if(!$fecha){
    echo json_encode([]);
    exit;
}

try{
    $pdo = new PDO("mysql:host=localhost;dbname=barberia;charset=utf8","root","");
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    // Horario de atención: 9:00 a 18:00, intervalos de 30 minutos
    $inicio = new DateTime('09:00');
    $fin = new DateTime('18:00');
    $horas_disponibles = [];

    while($inicio <= $fin){
        $horas_disponibles[] = $inicio->format('H:i:s'); // formato para DB
        $inicio->modify('+30 minutes');
    }

    // Traer turnos ya reservados en esa fecha
    $stmt = $pdo->prepare("SELECT hora FROM turnos WHERE fecha=?");
    $stmt->execute([$fecha]);
    $ocupadas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Filtrar horas ocupadas
    $libres = array_diff($horas_disponibles, $ocupadas);

    echo json_encode(array_values($libres));

}catch(PDOException $e){
    echo json_encode([]);
}

