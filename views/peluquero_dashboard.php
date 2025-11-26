<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Profesional - Barbería</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    
    <script>
        const CSRF_TOKEN = "<?php echo $_SESSION['csrf_token'] ?? ''; ?>";
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        }
    </script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Scroll elegante oscuro */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #171717; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #404040; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #d97706; }
        
        /* FullCalendar Dark Mode */
        .fc-toolbar-title { font-size: 1.1rem !important; font-weight: 700 !important; color: #e5e5e5 !important; }
        .fc-button { background-color: #d97706 !important; border: none !important; font-size: 0.8rem !important; font-weight: 600 !important; }
        .fc-button:hover { background-color: #b45309 !important; }
        .fc-button-active { background-color: #b45309 !important; }
        
        .fc-theme-standard .fc-scrollgrid { border-color: #262626 !important; }
        .fc-theme-standard td, .fc-theme-standard th { border-color: #262626 !important; }
        .fc-col-header-cell-cushion { color: #a3a3a3 !important; text-decoration: none !important; }
        .fc-daygrid-day-number { color: #d4d4d4 !important; text-decoration: none !important; }
        .fc-day-today { background-color: rgba(217, 119, 6, 0.1) !important; }
    </style>
</head>
<body class="bg-neutral-950 text-neutral-300 h-screen flex flex-col overflow-hidden selection:bg-amber-500 selection:text-white">

    <header class="bg-neutral-900/90 backdrop-blur-md border-b border-white/5 shadow-lg shrink-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <div class="flex items-center gap-2">
                <div class="text-amber-500 p-1.5 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm8.486-8.486a5 5 0 010 7.071L16.929 7.757 19.757 4.929a2.99 2.99 0 012.85.606z"></path></svg>
                </div>
                <h1 class="text-lg font-bold tracking-wide hidden sm:block text-white">THE<span class="text-amber-500">BARBER</span></h1>
            </div>

            <div class="flex items-center gap-6">
                
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-white"><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Profesional'); ?></p>
                    <p class="text-xs text-amber-500 font-medium tracking-wider">ESTILISTA</p>
                </div>

                <div class="relative group">
                    <button class="text-neutral-400 hover:text-white relative focus:outline-none p-1 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <?php if(isset($notificaciones) && count($notificaciones) > 0): ?>
                            <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-neutral-900"></span>
                        <?php endif; ?>
                    </button>
                    
                    <div class="absolute right-0 mt-2 w-80 bg-neutral-900 rounded-xl shadow-2xl border border-neutral-800 overflow-hidden z-50 hidden group-hover:block origin-top-right transition-all">
                        <div class="px-4 py-3 border-b border-neutral-800 bg-neutral-900">
                            <p class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Tus Avisos</p>
                        </div>
                        <div class="max-h-64 overflow-y-auto custom-scroll">
                            <?php if(isset($notificaciones) && count($notificaciones) > 0): ?>
                                <?php foreach($notificaciones as $noti): ?>
                                    <div class="px-4 py-3 border-b border-neutral-800 hover:bg-neutral-800 transition">
                                        <p class="text-sm text-neutral-300"><?php echo htmlspecialchars($noti['mensaje']); ?></p>
                                        <p class="text-xs text-neutral-500 mt-1"><?php echo date('d/m H:i', strtotime($noti['creado_en'])); ?></p>
                                    </div>
                                <?php endforeach; ?>
                                <a href="../controllers/marcar_leido.php" class="block w-full text-center text-xs text-amber-500 font-bold py-3 hover:bg-neutral-800 bg-neutral-900">Marcar todo leído</a>
                            <?php else: ?>
                                <div class="px-4 py-6 text-center text-neutral-500 text-sm">Todo al día ✨</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="h-6 w-px bg-neutral-700"></div>

                <a href="../controllers/auth_logout.php" class="bg-neutral-800 hover:bg-red-900/30 text-neutral-400 hover:text-red-400 p-2 rounded-lg transition border border-neutral-700 hover:border-red-900/50" title="Cerrar Sesión">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </a>
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-hidden p-4 lg:p-6 max-w-7xl mx-auto w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-full">
            
            <div class="lg:col-span-4 bg-neutral-900 rounded-2xl shadow-sm border border-neutral-800 flex flex-col h-full overflow-hidden">
                <div class="p-5 border-b border-neutral-800 bg-neutral-900 flex justify-between items-center">
                    <h2 class="font-bold text-white text-lg">Mis Turnos</h2>
                    <span class="bg-amber-900/30 text-amber-500 text-xs font-bold px-2.5 py-1 rounded-full border border-amber-500/20"><?php echo isset($turnos) ? count($turnos) : 0; ?></span>
                </div>
                
                <div class="overflow-y-auto flex-1 p-4 custom-scroll space-y-3 bg-neutral-950/50">
                    <?php if(isset($turnos) && count($turnos) > 0): ?>
                        <?php foreach ($turnos as $t): ?>
                            <?php 
                                // Estilos adaptados a Dark Mode
                                $borde = 'border-l-amber-500';
                                $estadoClass = 'bg-amber-900/30 text-amber-400 border border-amber-700/50';
                                $estadoTexto = 'Pendiente';
                                
                                if($t['estado'] == 'confirmado') { 
                                    $borde = 'border-l-emerald-500'; 
                                    $estadoClass = 'bg-emerald-900/30 text-emerald-400 border border-emerald-700/50';
                                    $estadoTexto = 'Confirmado';
                                } elseif($t['estado'] == 'cancelado' || $t['estado'] == 'cancelado_cliente') { 
                                    $borde = 'border-l-rose-500'; 
                                    $estadoClass = 'bg-rose-900/30 text-rose-400 border border-rose-700/50';
                                    $estadoTexto = 'Cancelado';
                                }
                            ?>
                            
                            <div class="bg-neutral-900 p-4 rounded-xl shadow-sm border border-neutral-800 border-l-4 <?php echo $borde; ?> hover:border-neutral-700 transition duration-200 group">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-neutral-500 uppercase tracking-wider mb-0.5">Cliente</span>
                                        <h3 class="font-bold text-white text-lg leading-tight"><?php echo htmlspecialchars($t['nombre_cliente']); ?></h3>
                                        <p class="text-sm text-amber-600 font-medium mt-1"><?php echo htmlspecialchars($t['servicio'] ?? 'Servicio General'); ?></p>
                                    </div>
                                    <span class="<?php echo $estadoClass; ?> text-[10px] uppercase font-bold px-2 py-1 rounded-md">
                                        <?php echo $estadoTexto; ?>
                                    </span>
                                </div>

                                <div class="flex items-center gap-4 text-sm text-neutral-400 bg-neutral-950 p-2 rounded-lg border border-neutral-800">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span><?php echo date("d M", strtotime($t['fecha'])); ?></span>
                                    </div>
                                    <div class="h-4 w-px bg-neutral-700"></div>
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span><?php echo substr($t['hora'], 0, 5); ?> hs</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2 mt-4 pt-3 border-t border-neutral-800">
                                    <?php if($t['estado'] == 'pendiente'): ?>
                                        <button onclick="cambiarEstado(<?php echo $t['id']; ?>, 'confirmado')" class="bg-emerald-900/20 hover:bg-emerald-900/40 text-emerald-500 py-2 rounded-lg text-xs font-bold transition border border-emerald-800 flex justify-center items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            Aceptar
                                        </button>
                                        <button onclick="cambiarEstado(<?php echo $t['id']; ?>, 'cancelado')" class="bg-rose-900/20 hover:bg-rose-900/40 text-rose-500 py-2 rounded-lg text-xs font-bold transition border border-rose-800 flex justify-center items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Rechazar
                                        </button>
                                    <?php elseif($t['estado'] == 'confirmado'): ?>
                                        <button onclick="cambiarEstado(<?php echo $t['id']; ?>, 'cancelado')" class="col-span-2 text-rose-500 hover:text-rose-400 text-xs font-medium hover:bg-rose-900/20 py-2 rounded transition">
                                            Cancelar Turno Confirmado
                                        </button>
                                    <?php else: ?>
                                        <div class="col-span-2 text-center text-xs text-neutral-600 italic py-1">Gestión finalizada</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="flex flex-col items-center justify-center h-64 text-neutral-500">
                            <svg class="w-12 h-12 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-sm font-medium">No tenés turnos asignados.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="lg:col-span-8 bg-neutral-900 rounded-2xl shadow-sm border border-neutral-800 p-4 h-full flex flex-col relative">
                <div id='calendar' class="flex-1 z-0 text-neutral-300"></div>
                
                <div class="hidden md:flex absolute bottom-6 right-6 bg-neutral-800/90 backdrop-blur px-4 py-2 rounded-lg shadow-lg border border-neutral-700 text-xs gap-4 z-10 text-neutral-300">
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Pendiente</div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span> Confirmado</div>
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
                buttonText: { today: 'Hoy', month: 'Mes', week: 'Semana', list: 'Lista' },
                events: 'ApiController.php',
                eventContent: function(arg) {
                    // Cajitas del calendario estilizadas
                    let italic = arg.event.extendedProps.estado === 'Pendiente' ? 'italic' : '';
                    return {
                        html: `<div class="px-1 py-0.5 overflow-hidden ${italic}">
                                <div class="font-bold text-[10px]">${arg.timeText}</div>
                                <div class="text-[11px] truncate leading-tight">${arg.event.title}</div>
                               </div>`
                    };
                },
                eventClick: function(info) {
                    const props = info.event.extendedProps;
                    // SweetAlert Dark Mode
                    Swal.fire({
                        title: `<span class="text-white text-lg font-bold">Detalle del Turno</span>`,
                        background: '#171717',
                        color: '#e5e5e5',
                        html: `
                            <div class="text-left bg-neutral-800 p-4 rounded-lg border border-neutral-700 text-sm space-y-2">
                                <p><strong class="text-amber-500">📅 Fecha:</strong> ${info.event.start.toLocaleDateString()}</p>
                                <p><strong class="text-amber-500">⏰ Hora:</strong> ${info.event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                                <div class="border-t border-neutral-700 my-2"></div>
                                <p><strong>👤 Cliente:</strong> <span class="text-white font-bold">${info.event.title.split(' - ')[1]}</span></p>
                                <p><strong>🏷️ Estado:</strong> ${props.estado}</p>
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
                title: '¿Actualizar turno?',
                text: `El estado pasará a: ${nuevoEstado.toUpperCase()}`,
                icon: 'question',
                background: '#171717',
                color: '#fff',
                showCancelButton: true,
                confirmButtonColor: '#d97706', // Amber
                cancelButtonColor: '#525252', // Neutral
                confirmButtonText: 'Sí, confirmar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                try {
                    const formData = new FormData();
                    formData.append('id', idTurno);
                    formData.append('estado', nuevoEstado);
                    formData.append('csrf_token', CSRF_TOKEN);

                    const res = await fetch('../controllers/AdminController.php', { 
                        method: 'POST', body: formData 
                    });
                    const data = await res.json();

                    if(data.status === 'ok') {
                        if (data.wa_link) {
                            const confirmarWa = await Swal.fire({
                                title: '¡Listo!',
                                text: '¿Querés avisar al cliente por WhatsApp?',
                                icon: 'success',
                                background: '#171717',
                                color: '#fff',
                                showCancelButton: true,
                                confirmButtonColor: '#25D366', 
                                cancelButtonColor: '#525252',
                                confirmButtonText: 'Sí, abrir WhatsApp',
                                cancelButtonText: 'No, solo guardar'
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
                        Swal.fire({ title: 'Error', text: 'No se pudo actualizar', icon: 'error', background: '#171717', color: '#fff' }); 
                    }
                } catch (error) { 
                    console.error(error); 
                    Swal.fire({ title: 'Error', text: 'Fallo de conexión', icon: 'error', background: '#171717', color: '#fff' });
                }
            }
        }
    </script>
</body>
</html>