<?php
// controllers/PublicConfig.php
header('Content-Type: application/json');
require_once '../models/Configuracion.php';

// Devolvemos solo lo necesario públicamente
$datos = [
    'mapa' => Configuracion::obtener('mapa_url'),
    'direccion' => Configuracion::obtener('direccion'),
    'telefono' => Configuracion::obtener('telefono')
];

echo json_encode($datos);
?>