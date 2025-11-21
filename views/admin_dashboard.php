
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - Barbería</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    
    <style>
        /* Personalización pequeña para la barra de scroll */
        .scroll-personalizado::-webkit-scrollbar {
            width: 8px;
        }
        .scroll-personalizado::-webkit-scrollbar-track {
            background: #1f2937; 
        }
        .scroll-personalizado::-webkit-scrollbar-thumb {
            background: #4b5563; 
            border-radius: 4px;
        }
        .scroll-personalizado::-webkit-scrollbar-thumb:hover {
            background: #6b7280; 
        }
    </style>
</head>
<body class="bg-gray-900 text-white h-screen flex flex-col overflow-hidden">

    <header class="bg-gray-800 border-b border-gray-700 p-4 flex justify-between items-center shrink-0 z-10 shadow-md">
        <h1 class="text-2xl font-bold flex items-center gap-2">
            💈 Dashboard Admin
        </h1>
        <div class="flex items-center gap-4">
            <div class="text-sm text-gray-400 hidden md:block">
                <span class="inline-block w-3 h-3 bg-[#ca8a04] rounded-full mr-1"></span> Pendiente
                <span class="inline-block w-3 h-3 bg-[#16a34a] rounded-full mr-1 ml-3"></span> Confirmado
            </div>
            <a href="../controllers/auth_logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium transition">Cerrar Sesión</a>
        </div>
    </header>

    <div class="flex-1 p-4 overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 h-full">
            
            <div class="bg-gray-800 rounded-xl shadow-xl border border-gray-700 flex flex-col h-full col-span-1">
                <div class="p-4 border-b border-gray-700 bg-gray-800 rounded-t-xl">
                    <h2 class="font-bold text-lg text-gray-200">📋 Solicitudes Recientes</h2>
                </div>
                
                <div class="overflow-y-auto flex-1 p-2 scroll-personalizado">
                    <?php if(count($turnos) > 0): ?>
                        <div class="space-y-3">
                            <?php foreach ($turnos as $t): ?>
                                <div class="bg-gray-700 p-3 rounded-lg border-l-4 <?php echo ($t['estado']=='confirmado'?'border-green-500':($t['estado']=='cancelado'?'border-red-500':'border-yellow-500')); ?> hover:bg-gray-600 transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-bold text-white"><?php echo htmlspecialchars($t['nombre']); ?></p>
                                            <p class="text-sm text-gray-300">
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
                        <p class="text-gray-400 text-center mt-10">No hay turnos registrados.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white text-gray-900 rounded-xl shadow-xl border border-gray-200 p-4 h-full col-span-1 lg:col-span-2 overflow-hidden flex flex-col">
                <div id='calendar' class="flex-1"></div>
            </div>

        </div>
    </div>

    <script>
        // 1. Configuración del Calendario
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                height: '100%', // Importante para que se ajuste al contenedor
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: 'ApiController.php', // Carga los eventos del archivo PHP
                eventClick: function(info) {
                    // Alerta simple al hacer click en el calendario
                    Swal.fire({
                        title: info.event.title,
                        text: 'Fecha: ' + info.event.start.toLocaleString(),
                        icon: 'info'
                    });
                }
            });
            calendar.render();
        });

        // 2. Función para cambiar estado (Misma lógica que antes)
        async function cambiarEstado(idTurno, nuevoEstado) {
            const result = await Swal.fire({
                title: '¿Actualizar turno?',
                text: `Marcar como: ${nuevoEstado}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí'
            });

            if (result.isConfirmed) {
                try {
                    const formData = new FormData();
                    formData.append('id', idTurno);
                    formData.append('estado', nuevoEstado);

                    const res = await fetch('AdminController.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await res.json();

                    if(data.status === 'ok') {
                        // Recargamos la página para ver los cambios en Lista y Calendario a la vez
                        location.reload();
                    } else {
                        Swal.fire('Error', 'No se pudo actualizar', 'error');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }
    </script>

</body>
</html>