<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Barbería</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    
    <script>
        const CSRF_TOKEN = "<?php echo $_SESSION['csrf_token'] ?? ''; ?>";
        
        // Configuración de Tailwind para usar la fuente Inter
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Scroll elegante */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Ajustes FullCalendar para que se vea moderno */
        .fc-toolbar-title { font-size: 1.25rem !important; font-weight: 600 !important; }
        .fc-button { background-color: #3b82f6 !important; border: none !important; font-size: 0.875rem !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 h-screen flex flex-col overflow-hidden">

    <header class="bg-slate-900 text-white shadow-lg shrink-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <div class="flex items-center gap-2">
                <div class="bg-blue-600 p-1.5 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h1 class="text-lg font-bold tracking-wide">Admin<span class="text-blue-400">Panel</span></h1>
            </div>

            <div class="hidden md:flex items-center gap-6">
                <a href="../views/admin_servicios.php" class="text-slate-300 hover:text-white text-sm font-medium transition">Gestión Servicios</a>
                <a href="../views/admin_crear_peluquero.php" class="text-slate-300 hover:text-white text-sm font-medium transition">Nuevo Profesional</a>
            </div>

            <div class="flex items-center gap-4">
                
                <div class="relative group">
                    <button class="text-slate-300 hover:text-white relative focus:outline-none p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <?php if(count($notificaciones) > 0): ?>
                            <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-slate-900"></span>
                        <?php endif; ?>
                    </button>
                    
                    <div class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden z-50 hidden group-hover:block origin-top-right transition-all">
                        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Notificaciones</p>
                        </div>
                        <div class="max-h-64 overflow-y-auto custom-scroll">
                            <?php if(count($notificaciones) > 0): ?>
                                <?php foreach($notificaciones as $noti): ?>
                                    <div class="px-4 py-3 border-b border-slate-50 hover:bg-blue-50 transition">
                                        <p class="text-sm text-slate-700"><?php echo htmlspecialchars($noti['mensaje']); ?></p>
                                        <p class="text-xs text-slate-400 mt-1 text-right"><?php echo date('H:i', strtotime($noti['creado_en'])); ?></p>
                                    </div>
                                <?php endforeach; ?>
                                <a href="../controllers/marcar_leido.php" class="block w-full text-center text-xs text-blue-600 font-bold py-3 hover:bg-slate-50 bg-slate-50">Marcar todo como leído</a>
                            <?php else: ?>
                                <div class="px-4 py-6 text-center text-slate-400 text-sm">Nada nuevo por aquí 😴</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="h-6 w-px bg-slate-700 mx-2"></div>

                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-white"><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Admin'); ?></p>
                        <p class="text-xs text-slate-400">Administrador</p>
                    </div>
                    <a href="../controllers/auth_logout.php" class="bg-slate-800 hover:bg-red-600 text-slate-300 hover:text-white p-2 rounded-lg transition" title="Salir">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-hidden p-4 lg:p-6 max-w-7xl mx-auto w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-full">
            
            <div class="lg:col-span-4 bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-full overflow-hidden">
                <div class="p-5 border-b border-slate-100 bg-white flex justify-between items-center">
                    <h2 class="font-bold text-slate-800 text-lg">Solicitudes</h2>
                    <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded-full"><?php echo count($turnos); ?></span>
                </div>
                
                <div class="overflow-y-auto flex-1 p-4 custom-scroll space-y-3 bg-slate-50">
                    <?php if(count($turnos) > 0): ?>
                        <?php foreach ($turnos as $t): ?>
                            <?php 
                                $borde = 'border-l-amber-400';
                                $bgBadge = 'bg-amber-100 text-amber-700';
                                $estado = 'Pendiente';
                                if($t['estado'] == 'confirmado') { $borde = 'border-l-emerald-500'; $bgBadge = 'bg-emerald-100 text-emerald-700'; $estado = 'Confirmado'; }
                                if($t['estado'] == 'cancelado' || $t['estado'] == 'cancelado_cliente') { $borde = 'border-l-rose-500'; $bgBadge = 'bg-rose-100 text-rose-700'; $estado = 'Cancelado'; }
                            ?>
                            
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 border-l-4 <?php echo $borde; ?> hover:shadow-md transition duration-200 group">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="font-bold text-slate-800"><?php echo htmlspecialchars($t['nombre']); ?></h3>
                                        <p class="text-xs text-slate-500 flex items-center gap-1 mt-1">
                                            <span>📅 <?php echo date("d M", strtotime($t['fecha'])); ?></span>
                                            <span>⏰ <?php echo substr($t['hora'], 0, 5); ?></span>
                                        </p>
                                        <?php if(!empty($t['peluquero'])): ?>
                                            <p class="text-xs text-blue-500 mt-1 font-medium">✂️ <?php echo htmlspecialchars($t['peluquero']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <span class="<?php echo $bgBadge; ?> text-[10px] uppercase font-bold px-2 py-0.5 rounded shadow-sm">
                                        <?php echo $estado; ?>
                                    </span>
                                </div>

                                <div class="flex gap-2 mt-3 pt-3 border-t border-slate-50 opacity-90 group-hover:opacity-100 transition">
                                    <?php if($t['estado'] == 'pendiente'): ?>
                                        <button onclick="cambiarEstado(<?php echo $t['id']; ?>, 'confirmado')" class="flex-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 py-1.5 rounded-lg text-xs font-bold transition border border-emerald-200">
                                            Aceptar
                                        </button>
                                        <button onclick="cambiarEstado(<?php echo $t['id']; ?>, 'cancelado')" class="flex-1 bg-rose-50 hover:bg-rose-100 text-rose-600 py-1.5 rounded-lg text-xs font-bold transition border border-rose-200">
                                            Rechazar
                                        </button>
                                    <?php elseif($t['estado'] == 'confirmado'): ?>
                                        <button onclick="cambiarEstado(<?php echo $t['id']; ?>, 'cancelado')" class="w-full text-rose-500 hover:text-rose-700 text-xs font-medium underline text-center">
                                            Cancelar Turno
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="flex flex-col items-center justify-center h-full text-slate-400">
                            <p class="text-sm">No hay turnos registrados.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="lg:col-span-8 bg-white rounded-2xl shadow-sm border border-slate-200 p-4 h-full flex flex-col relative">
                <div id='calendar' class="flex-1 z-0"></div>
                
                <div class="absolute bottom-6 right-6 bg-white/90 backdrop-blur p-3 rounded-lg shadow-lg border border-slate-100 text-xs flex gap-4 z-10">
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Pendiente</div>
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-600"></span> Confirmado</div>
                </div>
            </div>

        </div>
    </div>

    <script>
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
                dayMaxEvents: true, // Mostrar "+2 más" si hay muchos
                events: 'ApiController.php',
                eventContent: function(arg) {
                    // Personalizar las cajitas del calendario
                    return {
                        html: `<div class="fc-content p-1 overflow-hidden">
                                <div class="font-bold text-[10px]">${arg.timeText}</div>
                                <div class="text-[11px] truncate">${arg.event.title}</div>
                               </div>`
                    };
                },
                eventClick: function(info) {
                    // [MEJORA] Popup con datos detallados del cliente y peluquero
                    const props = info.event.extendedProps;
                    Swal.fire({
                        title: `<span class="text-lg font-bold text-slate-700">Detalle del Turno</span>`,
                        html: `
                            <div class="text-left bg-slate-50 p-4 rounded-lg border border-slate-200 text-sm space-y-2">
                                <p><strong>📅 Fecha:</strong> ${info.event.start.toLocaleDateString()}</p>
                                <p><strong>⏰ Hora:</strong> ${info.event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                                <div class="border-t border-slate-200 my-2"></div>
                                <p><strong>👤 Cliente:</strong> <span class="text-blue-600 font-medium">${props.cliente}</span></p>
                                <p><strong>✂️ Profesional:</strong> ${props.peluquero}</p>
                                <p><strong>Estado:</strong> <span class="px-2 py-0.5 rounded text-xs font-bold 
                                    ${props.estado === 'Confirmado' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'}">
                                    ${props.estado}</span>
                                </p>
                            </div>
                        `,
                        showConfirmButton: false,
                        showCloseButton: true
                    });
                }
            });
            calendar.render();
        });

        async function cambiarEstado(idTurno, nuevoEstado) {
            const result = await Swal.fire({
                title: '¿Actualizar estado?',
                text: `El turno pasará a: ${nuevoEstado.toUpperCase()}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Sí, actualizar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                try {
                    const formData = new FormData();
                    formData.append('id', idTurno);
                    formData.append('estado', nuevoEstado);
                    formData.append('csrf_token', CSRF_TOKEN);

                    const res = await fetch('AdminController.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await res.json();

                    if(data.status === 'ok') {
                        if (data.wa_link) {
                            const confirmarWa = await Swal.fire({
                                title: '¡Listo!',
                                text: '¿Querés notificar al cliente por WhatsApp?',
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonColor: '#25D366', 
                                cancelButtonColor: '#64748b',
                                confirmButtonText: 'Sí, abrir WhatsApp',
                                cancelButtonText: 'No, gracias'
                            });
                            if (confirmarWa.isConfirmed) window.open(data.wa_link, '_blank');
                        } else {
                            const Toast = Swal.mixin({
                                toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
                            });
                            Toast.fire({ icon: 'success', title: 'Estado actualizado' });
                        }
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        Swal.fire('Error', 'No se pudo actualizar', 'error');
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire('Error', 'Fallo de conexión', 'error');
                }
            }
        }
    </script>
</body>
</html>