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
    
    <div class="relative group mr-4">
    <button class="text-gray-300 hover:text-white focus:outline-none">
        🔔
        <?php if(count($notificaciones) > 0): ?>
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                <?php echo count($notificaciones); ?>
            </span>
        <?php endif; ?>
    </button>

    <div class="absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg overflow-hidden z-20 hidden group-hover:block">
        <div class="py-2">
            <?php if(count($notificaciones) > 0): ?>
                <?php foreach($notificaciones as $noti): ?>
                    <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50">
                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($noti['mensaje']); ?></p>
                        <p class="text-xs text-gray-400 mt-1"><?php echo $noti['creado_en']; ?></p>
                    </div>
                <?php endforeach; ?>
                <a href="../controllers/marcar_leido.php" class="block text-center text-sm text-blue-500 py-2 hover:bg-gray-100">Marcar como leídas</a>
            <?php else: ?>
                <p class="px-4 py-3 text-sm text-gray-500 text-center">No tienes notificaciones nuevas.</p>
            <?php endif; ?>
        </div>
        </div>
    </div>
    
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
<div id="wizard-reserva" class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-xl shadow mb-10" style="display: none;">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">💈 Nueva Reserva</h2>

    <div class="flex justify-between mb-8 text-sm font-medium text-gray-500">
        <span id="progreso-1" class="text-blue-600 font-bold">1. Servicio</span>
        <span id="progreso-2">2. Profesional</span>
        <span id="progreso-3">3. Fecha y Hora</span>
        <span id="progreso-4">4. Confirmar</span>
    </div>

    <div id="step-1" class="step-content">
        <h3 class="text-xl font-semibold mb-4">¿Qué te querés hacer?</h3>
        <div id="lista-servicios" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <p class="text-gray-500">Cargando servicios...</p>
        </div>
    </div>

    <div id="step-2" class="step-content hidden">
        <h3 class="text-xl font-semibold mb-4">Elegí tu profesional</h3>
        <button onclick="volverPaso(1)" class="mb-4 text-sm text-blue-500 hover:underline">← Volver a servicios</button>
        <div id="lista-peluqueros" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            </div>
    </div>

    <div id="step-3" class="step-content hidden">
        <h3 class="text-xl font-semibold mb-4">Elegí turno</h3>
        <button onclick="volverPaso(2)" class="mb-4 text-sm text-blue-500 hover:underline">← Volver a profesionales</button>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-bold mb-2">Fecha</label>
                <input type="date" id="wizard-fecha" class="w-full border p-2 rounded" min="<?php echo date('Y-m-d'); ?>">
            </div>
            <div>
                <label class="block font-bold mb-2">Horarios Disponibles</label>
                <div id="grilla-horas" class="grid grid-cols-3 gap-2">
                    <p class="col-span-3 text-gray-500 text-sm">Seleccioná una fecha primero.</p>
                </div>
            </div>
        </div>
    </div>

    <div id="step-4" class="step-content hidden">
        <h3 class="text-xl font-semibold mb-4">Confirmar Reserva</h3>
        <div class="bg-gray-50 p-4 rounded border border-gray-200 mb-6">
            <p><strong>Servicio:</strong> <span id="resumen-servicio">-</span></p>
            <p><strong>Profesional:</strong> <span id="resumen-peluquero">-</span></p>
            <p><strong>Fecha:</strong> <span id="resumen-fecha">-</span></p>
            <p><strong>Hora:</strong> <span id="resumen-hora">-</span></p>
            <p class="mt-2 text-xl font-bold text-blue-600">Total: $<span id="resumen-precio">0</span></p>
        </div>
        
        <div class="flex gap-4">
            <button onclick="volverPaso(3)" class="px-4 py-2 border rounded hover:bg-gray-100">Atrás</button>
            <button id="btn-confirmar-final" class="flex-1 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-bold">
                ✅ Confirmar Reserva
            </button>
        </div>
    </div>
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







