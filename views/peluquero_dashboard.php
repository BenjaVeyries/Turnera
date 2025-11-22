<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Profesional - Barbería</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script>
    const CSRF_TOKEN = "<?php echo $_SESSION['csrf_token'] ?? ''; ?>";
    </script>
    <style>
        .scroll-personalizado::-webkit-scrollbar { width: 8px; }
        .scroll-personalizado::-webkit-scrollbar-track { background: #1f2937; }
        .scroll-personalizado::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        .scroll-personalizado::-webkit-scrollbar-thumb:hover { background: #6b7280; }
    </style>
</head>
<body class="bg-gray-900 text-white h-screen flex flex-col overflow-hidden">

    <header class="bg-gray-800 border-b border-gray-700 p-4 flex justify-between items-center shrink-0 z-10 shadow-md">
        <h1 class="text-2xl font-bold flex items-center gap-2">
            ✂️ Panel del Profesional
        </h1>
        
        <div class="flex items-center gap-4">
            <p class="font-semibold text-gray-300">Hola, <?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Profesional'); ?></p>
            
            <div class="relative group">
                <button class="text-gray-300 hover:text-white focus:outline-none">
                    🔔
                    <?php if(isset($notificaciones) && count($notificaciones) > 0): ?>
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                            <?php echo count($notificaciones); ?>
                        </span>
                    <?php endif; ?>
                </button>
                <div class="absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg overflow-hidden z-20 hidden group-hover:block">
                    <div class="py-2 text-gray-800">
                        <?php if(isset($notificaciones) && count($notificaciones) > 0): ?>
                            <?php foreach($notificaciones as $noti): ?>
                                <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50">
                                    <p class="text-sm"><?php echo htmlspecialchars($noti['mensaje']); ?></p>
                                    <p class="text-xs text-gray-400 mt-1"><?php echo $noti['creado_en']; ?></p>
                                </div>
                            <?php endforeach; ?>
                            <a href="../controllers/marcar_leido.php" class="block text-center text-sm text-blue-500 py-2 hover:bg-gray-100">Marcar leídas</a>
                        <?php else: ?>
                            <p class="px-4 py-3 text-sm text-gray-500 text-center">Sin novedades.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <a href="../controllers/auth_logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium transition">Cerrar Sesión</a>
        </div>
    </header>

    <div class="flex-1 p-4 overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 h-full">
            
            <div class="bg-gray-800 rounded-xl shadow-xl border border-gray-700 flex flex-col h-full col-span-1">
                <div class="p-4 border-b border-gray-700 bg-gray-800 rounded-t-xl">
                    <h2 class="font-bold text-lg text-gray-200">📅 Mis Turnos Asignados</h2>
                </div>
                
                <div class="overflow-y-auto flex-1 p-2 scroll-personalizado">
                    <?php if(isset($turnos) && count($turnos) > 0): ?>
                        <div class="space-y-3">
                            <?php foreach ($turnos as $t): ?>
                                <div class="bg-gray-700 p-3 rounded-lg border-l-4 <?php echo ($t['estado']=='confirmado'?'border-green-500':($t['estado']=='cancelado'?'border-red-500':'border-yellow-500')); ?> hover:bg-gray-600 transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-bold text-white"><?php echo htmlspecialchars($t['nombre_cliente']); ?></p>
                                            <p class="text-sm text-blue-300 font-medium"><?php echo htmlspecialchars($t['servicio'] ?? 'Corte'); ?></p>
                                            
                                            <p class="text-sm text-gray-300 mt-1">
                                                📅 <?php echo date("d/m", strtotime($t['fecha'])); ?> 
                                                ⏰ <?php echo substr($t['hora'], 0, 5); ?>
                                            </p>
                                            <span class="text-xs font-bold uppercase mt-1 inline-block 
                                                <?php echo ($t['estado']=='confirmado'?'text-green-400':($t['estado']=='cancelado'?'text-red-400':'text-yellow-400')); ?>">
                                                <?php echo $t['estado']; ?>
                                            </span>
                                        </div>

                                        <div class="flex flex-col gap-2">
                                            <?php if($t['estado'] == 'pendiente'): ?>
                                                <button onclick="cambiarEstado(<?php echo $t['id']; ?>, 'confirmado')" class="bg-green-600 p-1.5 rounded text-white hover:bg-green-500" title="Aceptar">✅</button>
                                                <button onclick="cambiarEstado(<?php echo $t['id']; ?>, 'cancelado')" class="bg-red-600 p-1.5 rounded text-white hover:bg-red-500" title="Rechazar">❌</button>
                                            <?php elseif($t['estado'] == 'confirmado'): ?>
                                                <button onclick="cambiarEstado(<?php echo $t['id']; ?>, 'cancelado')" class="text-xs text-red-400 hover:underline">Cancelar</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-400 text-center mt-10">No tenés turnos asignados.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white text-gray-900 rounded-xl shadow-xl border border-gray-200 p-4 h-full col-span-1 lg:col-span-2 overflow-hidden flex flex-col">
                <div id='calendar' class="flex-1"></div>
            </div>

        </div>
    </div>

    <script>
        // Reutilizamos la misma lógica de JS
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                height: '100%', 
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: 'ApiController.php', // Asegurate de modificar ApiController para permitir Peluqueros
                eventClick: function(info) {
                    Swal.fire({
                        title: info.event.title,
                        text: 'Fecha: ' + info.event.start.toLocaleString(),
                        icon: 'info'
                    });
                }
            });
            calendar.render();
        });

        async function cambiarEstado(idTurno, nuevoEstado) {
            // ... Misma función JS que en admin_dashboard ...
            const result = await Swal.fire({
                title: '¿Actualizar turno?',
                text: `Marcar como: ${nuevoEstado}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí'
            });

            if (result.isConfirmed) {
                try {
                    const formData = new FormData();
                    formData.append('id', idTurno);
                    formData.append('estado', nuevoEstado);
                    formData.append('csrf_token', CSRF_TOKEN);

                    // IMPORTANTE: AdminController debe permitir rol 'Peluquero'
                    const res = await fetch('AdminController.php', { 
                        method: 'POST', body: formData 
                    });
                    const data = await res.json();

                    if(data.status === 'ok') { location.reload(); } 
                    else { Swal.fire('Error', 'No se pudo actualizar', 'error'); }
                } catch (error) { console.error(error); }
            }
        }
    </script>
</body>
</html>