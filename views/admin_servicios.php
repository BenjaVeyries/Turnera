<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión Servicios</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <h1 class="text-2xl font-bold mb-5">Gestión de Servicios</h1>
    
    <form action="../controllers/AdminServicioController.php" method="POST" class="bg-white p-6 rounded shadow mb-8 max-w-lg">
        <input type="hidden" name="accion" value="crear_servicio">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
        <label class="block mb-2">Nombre del Servicio</label>
        <input type="text" name="nombre" required class="w-full border p-2 rounded mb-4" placeholder="Ej: Corte Fade">
        
        <label class="block mb-2">Precio</label>
        <input type="number" name="precio" required class="w-full border p-2 rounded mb-4" placeholder="5000">
        
        <label class="block mb-2">Duración (minutos)</label>
        <input type="number" name="duracion" required class="w-full border p-2 rounded mb-4" value="30">
        
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Guardar Servicio</button>
    </form>

    <a href="../controllers/AdminDashboard.php" class="text-blue-500 underline">Volver al Dashboard</a>
</body>
</html>