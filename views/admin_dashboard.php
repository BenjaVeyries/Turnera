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
        
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Scroll elegante oscuro */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #171717; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #404040; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #d97706; }

        /* FullCalendar Dark Mode Overrides */
        .fc-toolbar-title { font-size: 1.25rem !important; font-weight: 600 !important; color: #fff !important; }
        .fc-button { background-color: #d97706 !important; border: none !important; font-size: 0.875rem !important; font-weight: bold !important; }
        .fc-button:hover { background-color: #b45309 !important; }
        .fc-button-active { background-color: #b45309 !important; }
        
        .fc-theme-standard .fc-scrollgrid { border-color: #262626 !important; }
        .fc-theme-standard td, .fc-theme-standard th { border-color: #262626 !important; }
        .fc-col-header-cell-cushion { color: #a3a3a3 !important; text-decoration: none !important; }
        .fc-daygrid-day-number { color: #d4d4d4 !important; text-decoration: none !important; }
        .fc-day-today { background-color: rgba(217, 119, 6, 0.1) !important; } /* Amber tint */
    </style>
</head>
<body class="bg-neutral-950 text-neutral-300 h-screen flex flex-col overflow-hidden selection:bg-amber-500 selection:text-white">

    <header class="bg-neutral-900/90 backdrop-blur-md border-b border-white/5 shadow-lg shrink-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <div class="flex items-center gap-2">
                <div class="text-amber-500 p-1.5 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h1 class="text-lg font-bold tracking-wide text-white">Admin<span class="text-amber-500">Panel</span></h1>
            </div>

            <div class="hidden md:flex items-center gap-6">
                <a href="../views/admin_servicios.php" class="text-neutral-400 hover:text-amber-500 text-sm font-medium transition">Gestión Servicios</a>
                <a href="../views/admin_crear_peluquero.php" class="text-neutral-400 hover:text-amber-500 text-sm font-medium transition">Nuevo Profesional</a>
            </div>

            <div class="flex items-center gap-4">
                <div class="relative group">
                    <button class="text-neutral-400 hover:text-white relative focus:outline-none p-1 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <?php if(count($notificaciones) > 0): ?>
                            <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-neutral-900"></span>
                        <?php endif; ?>
                    </button>
                    
                    <div class="absolute right-0 mt-2 w-80 bg-neutral-900 rounded-xl shadow-2xl border border-neutral-800 overflow-hidden z-50 hidden group-hover:block origin-top-right transition-all">
                        <div class="px-4 py-3 border-b border-neutral-800 bg-neutral-900">
                            <p class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Notificaciones</p>
                        </div>
                        <div class="max-h-64 overflow-y-auto custom-scroll">
                            <?php if(count($notificaciones) > 0): ?>
                                <?php foreach($notificaciones as $noti): ?>
                                    <div class="px-4 py-3 border-b border-neutral-800 hover:bg-neutral-800 transition">
                                        <p class="text-sm text-neutral-300"><?php echo htmlspecialchars($noti['mensaje']); ?></p>
                                        <p class="text-xs text-neutral-500 mt-1 text-right"><?php echo date('H:i', strtotime($noti['creado_en'])); ?></p>
                                    </div>
                                <?php endforeach; ?>
                                <a href="../controllers/marcar_leido.php" class="block w-full text-center text-xs text-amber-500 font-bold py-3 hover:bg-neutral-800 bg-neutral-900">Marcar todo como leído</a>
                            <?php else: ?>
                                <div class="px-4 py-6 text-center text-neutral-500 text-sm">Nada nuevo por aquí 😴</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="h-6 w-px bg-neutral-700 mx-2"></div>

                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-white"><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Admin'); ?></p>
                        <p class="text-xs text-neutral-500">Administrador</p>
                    </div>
                    <a href="../controllers/auth_logout.php" class="bg-neutral-800 hover:bg-red-900/50 text-neutral-400 hover:text-red-400 p-2 rounded-lg transition border border-neutral-700 hover:border-red-800" title="Salir">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-4 lg:p-6 max-w-7xl mx-auto w-full custom-scroll">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8 h-[600px]">
            
            <div class="lg:col-span-4 bg-neutral-900 rounded-2xl shadow-sm border border-neutral-800 flex flex-col h-full overflow-hidden">
                <div class="p-5 border-b border-neutral-800 bg-neutral-900 flex justify-between items-center">
                    <h2 class="font-bold text-white text-lg">Solicitudes</h2>
                    <span class="bg-amber-900/30 text-amber-500 text-xs font-bold px-2 py-1 rounded-full border border-amber-500/20"><?php echo count($turnos); ?></span>
                </div>
                
                <div class="overflow-y-auto flex-1 p-4 custom-scroll space-y-3 bg-neutral-950/50">
                    <?php if(count($turnos) > 0): ?>
                        <?php foreach ($turnos as $t): ?>
                            <?php 
                                // Adaptación de estilos de estado para Dark Mode
                                $borde = 'border-l-amber-500';
                                $bgBadge = 'bg-amber-900/30 text-amber-400 border border-amber-700/50';
                                $estado = 'Pendiente';
                                
                                if($t['estado'] == 'confirmado') { 
                                    $borde = 'border-l-emerald-500'; 
                                    $bgBadge = 'bg-emerald-900/30 text-emerald-400 border border-emerald-700/50'; 
                                    $estado = 'Confirmado'; 
                                }
                                if($t['estado'] == 'cancelado' || $t['estado'] == 'cancelado_cliente') { 
                                    $borde = 'border-l-rose-500'; 
                                    $bgBadge = 'bg-rose-900/30 text-rose-400 border border-rose-700/50'; 
                                    $estado = 'Cancelado'; 
                                }
                            ?>
                            
                            <div class="bg-neutral-900 p-4 rounded-xl shadow-sm border border-neutral-800 border-l-4 <?php echo $borde; ?> hover:border-neutral-700 transition duration-200 group">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="font-bold text-white"><?php echo htmlspecialchars($t['nombre']); ?></h3>
                                        <p class="text-xs text-neutral-500 flex items-center gap-1 mt-1">
                                            <span>📅 <?php echo date("d M", strtotime($t['fecha'])); ?></span>
                                            <span>⏰ <?php echo substr($t['hora'], 0, 5); ?></span>
                                        </p>
                                        <?php if(!empty($t['peluquero'])): ?>
                                            <p class="text-xs text-amber-600 mt-1 font-medium">✂️ <?php echo htmlspecialchars($t['peluquero']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <span class="<?php echo $bgBadge; ?> text-[10px] uppercase font-bold px-2 py-0.5 rounded shadow-sm">
                                        <?php echo $estado; ?>
                                    </span>
                                </div>

                                <div class="flex gap-2 mt-3 pt-3 border-t border-neutral-800 opacity-90 group-hover:opacity-100 transition">
                                    <?php if($t['estado'] == 'pendiente'): ?>
                                        <button onclick="cambiarEstado(<?php echo $t['id']; ?>, 'confirmado')" class="flex-1 bg-emerald-900/20 hover:bg-emerald-900/40 text-emerald-500 py-1.5 rounded-lg text-xs font-bold transition border border-emerald-800 hover:border-emerald-600">
                                            Aceptar
                                        </button>
                                        <button onclick="cambiarEstado(<?php echo $t['id']; ?>, 'cancelado')" class="flex-1 bg-rose-900/20 hover:bg-rose-900/40 text-rose-500 py-1.5 rounded-lg text-xs font-bold transition border border-rose-800 hover:border-rose-600">
                                            Rechazar
                                        </button>
                                    <?php elseif($t['estado'] == 'confirmado'): ?>
                                        <button onclick="cambiarEstado(<?php echo $t['id']; ?>, 'cancelado')" class="w-full text-rose-500 hover:text-rose-400 text-xs font-medium underline text-center transition">
                                            Cancelar Turno
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="flex flex-col items-center justify-center h-full text-neutral-500">
                            <p class="text-sm">No hay turnos registrados.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="lg:col-span-8 bg-neutral-900 rounded-2xl shadow-sm border border-neutral-800 p-4 h-full flex flex-col relative">
                <div id='calendar' class="flex-1 z-0"></div>
                
                <div class="absolute bottom-6 right-6 bg-neutral-800/90 backdrop-blur p-3 rounded-lg shadow-lg border border-neutral-700 text-xs flex gap-4 z-10 text-neutral-300">
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Pendiente</div>
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-600"></span> Confirmado</div>
                </div>
            </div>
        </div>

        <div class="bg-neutral-900 rounded-2xl shadow-sm border border-neutral-800 p-6 mb-10">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                ⚙️ Configuración del Local
            </h3>
            
            <form action="../controllers/AdminConfigController.php" method="POST" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-neutral-500 uppercase mb-1">Dirección Visible</label>
                        <input type="text" name="direccion" value="<?php echo htmlspecialchars($config['direccion'] ?? ''); ?>" 
                               class="w-full bg-neutral-950 border border-neutral-800 rounded-lg p-2.5 text-sm text-white focus:ring-1 focus:ring-amber-500 focus:border-amber-500 outline-none transition"
                               placeholder="Ej: Av. Corrientes 1234">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-neutral-500 uppercase mb-1">Teléfono de Contacto</label>
                        <input type="text" name="telefono" value="<?php echo htmlspecialchars($config['telefono'] ?? ''); ?>" 
                               class="w-full bg-neutral-950 border border-neutral-800 rounded-lg p-2.5 text-sm text-white focus:ring-1 focus:ring-amber-500 focus:border-amber-500 outline-none transition"
                               placeholder="Ej: 11 2233 4455">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-neutral-500 uppercase mb-1">URL del Mapa (Google Maps Embed)</label>
                    <div class="flex gap-2">
                        <input type="text" name="mapa_url" value="<?php echo htmlspecialchars($config['mapa_url'] ?? ''); ?>" 
                               class="flex-1 bg-neutral-950 border border-neutral-800 rounded-lg p-2.5 text-sm font-mono text-neutral-400 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 outline-none transition"
                               placeholder="Pega aquí el enlace src=...">
                    </div>
                    <p class="text-[11px] text-neutral-500 mt-1">
                        ℹ️ Ve a Google Maps -> Compartir -> Insertar mapa -> Copia <b>solo lo que está dentro de src="..."</b>
                    </p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="bg-amber-600 text-white px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-amber-700 transition shadow-lg shadow-amber-900/20">
                        Guardar Cambios
                    </button>
                </div>
            </form>
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
                dayMaxEvents: true, 
                events: 'ApiController.php',
                eventContent: function(arg) {
                    return {
                        html: `<div class="fc-content p-1 overflow-hidden">
                                <div class="font-bold text-[10px]">${arg.timeText}</div>
                                <div class="text-[11px] truncate">${arg.event.title}</div>
                               </div>`
                    };
                },
                eventClick: function(info) {
                    const props = info.event.extendedProps;
                    // SWAL Adaptado a Dark Mode
                    Swal.fire({
                        title: `<span class="text-lg font-bold text-neutral-200">Detalle del Turno</span>`,
                        background: '#171717', // Neutral-900
                        color: '#e5e5e5', // Neutral-200
                        html: `
                            <div class="text-left bg-neutral-800 p-4 rounded-lg border border-neutral-700 text-sm space-y-2">
                                <p><strong class="text-amber-500">📅 Fecha:</strong> ${info.event.start.toLocaleDateString()}</p>
                                <p><strong class="text-amber-500">⏰ Hora:</strong> ${info.event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                                <div class="border-t border-neutral-700 my-2"></div>
                                <p><strong>👤 Cliente:</strong> <span class="text-white font-medium">${props.cliente}</span></p>
                                <p><strong>✂️ Profesional:</strong> <span class="text-neutral-300">${props.peluquero}</span></p>
                                <p><strong>Estado:</strong> <span class="px-2 py-0.5 rounded text-xs font-bold 
                                    ${props.estado === 'Confirmado' ? 'bg-emerald-900/50 text-emerald-400 border border-emerald-700' : 'bg-amber-900/50 text-amber-400 border border-amber-700'}">
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
                background: '#171717',
                color: '#fff',
                showCancelButton: true,
                confirmButtonColor: '#d97706', // Amber-600
                cancelButtonColor: '#525252', // Neutral-600
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
                                background: '#171717',
                                color: '#fff',
                                showCancelButton: true,
                                confirmButtonColor: '#25D366', 
                                cancelButtonColor: '#525252',
                                confirmButtonText: 'Sí, abrir WhatsApp',
                                cancelButtonText: 'No, gracias'
                            });
                            if (confirmarWa.isConfirmed) window.open(data.wa_link, '_blank');
                        } else {
                            const Toast = Swal.mixin({
                                toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
                                background: '#171717', color: '#fff'
                            });
                            Toast.fire({ icon: 'success', title: 'Estado actualizado' });
                        }
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        Swal.fire({ title:'Error', text:'No se pudo actualizar', icon:'error', background: '#171717', color: '#fff'});
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire({ title:'Error', text:'Fallo de conexión', icon:'error', background: '#171717', color: '#fff'});
                }
            }
        }
    </script>
</body>
</html>