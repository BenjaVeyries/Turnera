<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservar Turno</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Pasamos el token de sesión a una variable JS global
    const CSRF_TOKEN = "<?php echo $_SESSION['csrf_token'] ?? ''; ?>";
    </script>
    <script src="../public/turnos.js" defer></script>
</head>
<body class="bg-gray-100">


<header class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">Turnera Barbería</h1>
    <div class="flex items-center gap-4">
        <p class="font-semibold text-gray-600">Hola, <?php echo htmlspecialchars($nombre); ?></p>
        <a href="../controllers/auth_logout.php" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Cerrar sesión</a>
    </div>
</header>

<!-- MENSAJE INICIAL -->
<div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-xl shadow text-center">
    <h2 class="text-2xl font-bold mb-6">Bienvenido</h2>
    <p class="mb-4 text-gray-700">Inicio de sesión exitoso</p>
    <button id="btn-reserva" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Comenzar reserva</button>
</div>

<!-- FORMULARIO DE RESERVA -->
<div id="form-turno" class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-xl shadow mb-10" style="display: none;">
    <h2 class="text-2xl font-bold mb-6">Formulario de Turno</h2>
    <form id="reserva-form">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 font-semibold">Fecha</label>
                <input type="date" name="fecha" id="fecha-input" required class="w-full p-2 border rounded-lg">
            </div>
            <div>
                <label class="block mb-2 font-semibold">Hora</label>
                <select name="hora" id="hora-select" required class="w-full p-2 border rounded-lg">
                    <option value="">Seleccionar fecha primero</option>
                </select>
            </div>
        </div>
        <div class="mt-4 text-center">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">Reservar turno</button>
        </div>
    </form>
</div>

<!-- LISTA DE TURNOS -->
<div id="lista-turnos" class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow">
    <h2 class="text-2xl font-bold mb-6">Mis Turnos</h2>
    <div id="turnos-container">
    <?php if(!empty($turnos)): ?>
        <table class="w-full text-left border-collapse">
            
    <thead>
        <tr class="border-b">
            <th class="py-2">Fecha</th>
            <th class="py-2">Hora</th>
            <th class="py-2">Estado</th>
            <th class="py-2 text-right">Acción</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($turnos as $t): ?>
        <tr class="border-b hover:bg-gray-100">
            <td class="py-2"><?php echo htmlspecialchars($t['fecha']); ?></td>
            <td class="py-2"><?php echo substr($t['hora'], 0, 5); ?> hs</td>
            
            <td class="py-2">
                <?php 
                if($t['estado'] == 'confirmado') {
                    echo '<span class="text-green-600 font-bold">Confirmado</span>';
                } elseif($t['estado'] == 'cancelado' || $t['estado'] == 'cancelado_cliente') {
                    echo '<span class="text-red-500 line-through">Cancelado</span>';
                } else {
                    echo '<span class="text-yellow-600 font-bold">Pendiente</span>';
                }
                ?>
            </td>

            <td class="py-2 text-right">
                <?php 
                // Solo mostrar botón cancelar si NO está cancelado ya
                // Y opcionalmente: si la fecha es futura (puedes agregar esa lógica si quieres)
                if($t['estado'] != 'cancelado' && $t['estado'] != 'cancelado_cliente'): 
                ?>
                    <button onclick="cancelarTurno(<?php echo $t['id']; ?>)" 
                            class="bg-red-100 text-red-600 hover:bg-red-200 px-3 py-1 rounded text-sm transition">
                        Cancelar
                    </button>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
    <?php else: ?>
        <p class="text-gray-600">No tenés turnos reservados.</p>
    <?php endif; ?>
</div>
</div>



</body>
</html>







