<?php
// views/cliente_turnos.php
// Mantengo la lógica PHP original intacta
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas • The Barber</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        const CSRF_TOKEN = "<?php echo $_SESSION['csrf_token'] ?? ''; ?>";
        tailwind.config = {
            theme: { 
                extend: { 
                    fontFamily: { sans: ['Inter', 'sans-serif'], serif: ['Playfair Display', 'serif'] } 
                } 
            }
        }
    </script>
    <script src="../public/turnos.js" defer></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Animación suave */
        .step-content { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* Scroll oscuro */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #171717; }
        ::-webkit-scrollbar-thumb { background: #404040; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #d97706; }

        /* --- TRUCO VISUAL PARA MODO OSCURO SIN TOCAR JS --- */
        /* Forzamos estilos oscuros en elementos generados dinámicamente por turnos.js */
        #wizard-reserva .bg-white { background-color: #262626 !important; border-color: #404040 !important; color: #e5e5e5 !important; }
        #wizard-reserva .text-slate-800 { color: #ffffff !important; }
        #wizard-reserva .text-slate-500 { color: #a3a3a3 !important; }
        #wizard-reserva .border-slate-200 { border-color: #404040 !important; }
        #wizard-reserva .hover\:border-blue-500:hover { border-color: #d97706 !important; } /* Hover dorado */
        #wizard-reserva .peer:checked ~ div { border-color: #d97706 !important; background-color: #451a03 !important; } /* Selección dorada */
    </style>
</head>
<body class="bg-neutral-950 text-neutral-300 min-h-screen flex flex-col selection:bg-amber-500 selection:text-white">

    <header class="bg-neutral-900/90 backdrop-blur-md border-b border-white/5 shadow-lg sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <div class="flex items-center gap-2">
                <div class="text-amber-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h1 class="text-lg font-bold tracking-widest text-white font-serif">THE <span class="text-amber-500">BARBER</span></h1>
            </div>

            <div class="flex items-center gap-6">
                
                <div class="relative group">
                    <button class="text-neutral-400 hover:text-white focus:outline-none relative p-1 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <?php if(count($notificaciones) > 0): ?>
                            <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-neutral-900"></span>
                        <?php endif; ?>
                    </button>

                    <div class="absolute right-0 mt-2 w-80 bg-neutral-900 rounded-xl shadow-2xl border border-neutral-800 overflow-hidden z-50 hidden group-hover:block origin-top-right">
                        <div class="px-4 py-3 border-b border-neutral-800 bg-neutral-950">
                            <p class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Notificaciones</p>
                        </div>
                        <div class="max-h-64 overflow-y-auto custom-scroll">
                            <?php if(count($notificaciones) > 0): ?>
                                <?php foreach($notificaciones as $noti): ?>
                                    <div class="px-4 py-3 border-b border-neutral-800 hover:bg-neutral-800 transition">
                                        <p class="text-sm text-neutral-300"><?php echo htmlspecialchars($noti['mensaje']); ?></p>
                                        <p class="text-xs text-neutral-500 mt-1 text-right"><?php echo $noti['creado_en']; ?></p>
                                    </div>
                                <?php endforeach; ?>
                                <a href="../controllers/marcar_leido.php" class="block w-full text-center text-xs text-amber-500 font-bold py-3 hover:bg-neutral-800 bg-neutral-900">Marcar leídas</a>
                            <?php else: ?>
                                <p class="px-4 py-6 text-sm text-neutral-500 text-center">Sin novedades.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="hidden sm:flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-medium text-white"><?php echo htmlspecialchars($nombre); ?></p>
                        <p class="text-[10px] text-amber-600 uppercase tracking-wider font-bold">Cliente VIP</p>
                    </div>
                    <div class="h-8 w-px bg-neutral-800 mx-1"></div>
                    <a href="../controllers/auth_logout.php" class="text-sm text-neutral-400 hover:text-red-400 font-medium transition" title="Cerrar Sesión">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-5xl mx-auto w-full p-6 space-y-10">

        <div class="bg-neutral-900 rounded-2xl p-8 shadow-2xl border border-neutral-800 text-center md:text-left flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10">
                <h2 class="text-2xl md:text-3xl font-bold text-white font-serif">Tu Estilo, Tu Tiempo</h2>
                <p class="text-neutral-400 mt-2 font-light">Gestiona tus próximas citas o agenda una nueva experiencia.</p>
            </div>
            <button id="btn-reserva" class="relative z-10 bg-amber-600 hover:bg-amber-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-amber-900/20 transition transform hover:-translate-y-0.5 active:scale-95 flex items-center gap-2 tracking-wide">
                <span>✂️</span> Reservar Turno
            </button>
        </div>

        <div id="wizard-reserva" class="bg-neutral-900 rounded-2xl shadow-2xl border border-neutral-800 overflow-hidden" style="display: none;">
            
            <div class="bg-neutral-950 px-8 py-4 border-b border-neutral-800 flex justify-between items-center">
                <h3 class="font-bold text-white tracking-wide">NUEVA RESERVA</h3>
                <button onclick="location.reload()" class="text-xs text-neutral-500 hover:text-red-500 transition uppercase font-bold">✕ Cancelar</button>
            </div>

            <div class="px-8 pt-6">
                <div class="flex justify-between text-[10px] font-bold tracking-[0.2em] uppercase text-neutral-500">
                    <span id="progreso-1" class="transition duration-300 text-amber-500">1. Servicio</span>
                    <span id="progreso-2" class="transition duration-300">2. Profesional</span>
                    <span id="progreso-3" class="transition duration-300">3. Horario</span>
                    <span id="progreso-4" class="transition duration-300">4. Confirmar</span>
                </div>
                <div class="h-1 w-full bg-neutral-800 mt-3 rounded-full overflow-hidden">
                    <div class="h-full bg-amber-600 w-0 transition-all duration-500 shadow-[0_0_10px_rgba(217,119,6,0.5)]" id="barra-progreso"></div>
                </div>
            </div>

            <div class="p-8">
                <div id="step-1" class="step-content">
                    <h3 class="text-2xl font-bold text-white mb-6 text-center font-serif">Elige tu Ritual</h3>
                    <div id="lista-servicios" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <p class="text-neutral-500 text-center col-span-2 animate-pulse">Cargando catálogo...</p>
                    </div>
                </div>

                <div id="step-2" class="step-content hidden">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-white font-serif">Selecciona Profesional</h3>
                        <button onclick="volverPaso(1)" class="text-sm text-amber-500 hover:text-amber-400 transition">← Volver</button>
                    </div>
                    <div id="lista-peluqueros" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6"></div>
                </div>

                <div id="step-3" class="step-content hidden">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-white font-serif">Define el Momento</h3>
                        <button onclick="volverPaso(2)" class="text-sm text-amber-500 hover:text-amber-400 transition">← Volver</button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-neutral-500 mb-2 uppercase tracking-wider">Fecha</label>
                            <input type="date" id="wizard-fecha" class="w-full bg-neutral-950 border border-neutral-800 p-3 rounded-xl focus:ring-1 focus:ring-amber-500 outline-none text-white color-scheme-dark" min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-neutral-500 mb-2 uppercase tracking-wider">Horarios Disponibles</label>
                            <div id="grilla-horas" class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                <p class="col-span-full text-neutral-600 text-sm italic bg-neutral-950 p-4 rounded-lg text-center border border-dashed border-neutral-800">
                                    👈 Selecciona una fecha primero.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="step-4" class="step-content hidden">
                    <div class="text-center max-w-md mx-auto">
                        <div class="w-16 h-16 bg-amber-500/10 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-amber-500/20">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2 font-serif">Resumen de la Cita</h3>
                        <p class="text-neutral-500 mb-8 text-sm">Todo listo. Confirma para agendar.</p>

                        <div class="bg-neutral-950 p-6 rounded-2xl border border-neutral-800 text-left space-y-3 mb-8">
                            <div class="flex justify-between border-b border-neutral-800 pb-2">
                                <span class="text-neutral-500 text-sm">Servicio</span>
                                <span class="font-bold text-white" id="resumen-servicio">-</span>
                            </div>
                            <div class="flex justify-between border-b border-neutral-800 pb-2">
                                <span class="text-neutral-500 text-sm">Profesional</span>
                                <span class="font-bold text-white" id="resumen-peluquero">-</span>
                            </div>
                            <div class="flex justify-between border-b border-neutral-800 pb-2">
                                <span class="text-neutral-500 text-sm">Fecha</span>
                                <span class="font-bold text-white" id="resumen-fecha">-</span>
                            </div>
                            <div class="flex justify-between border-b border-neutral-800 pb-2">
                                <span class="text-neutral-500 text-sm">Hora</span>
                                <span class="font-bold text-white" id="resumen-hora">-</span>
                            </div>
                            <div class="flex justify-between pt-2 text-lg">
                                <span class="text-amber-500 font-bold">Total Estimado</span>
                                <span class="font-bold text-white">$<span id="resumen-precio">0</span></span>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <button onclick="volverPaso(3)" class="w-1/3 py-3 border border-neutral-700 rounded-xl text-neutral-400 font-bold hover:bg-neutral-800 transition">Atrás</button>
                            <button id="btn-confirmar-final" class="w-2/3 bg-amber-600 text-white py-3 rounded-xl font-bold hover:bg-amber-700 shadow-lg shadow-amber-900/20 transition transform active:scale-[0.98]">
                                Confirmar Reserva
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="text-xl font-bold text-white flex items-center gap-2 font-serif">
                📜 Mis Turnos
            </h3>

            <div class="bg-neutral-900 rounded-2xl shadow-sm border border-neutral-800 overflow-hidden">
                <?php if(!empty($turnos)): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-neutral-950 text-neutral-500 uppercase text-xs font-bold tracking-wider">
                                <tr>
                                    <th class="py-4 px-6 border-b border-neutral-800">Fecha</th>
                                    <th class="py-4 px-6 border-b border-neutral-800">Hora</th>
                                    <th class="py-4 px-6 border-b border-neutral-800">Estado</th>
                                    <th class="py-4 px-6 border-b border-neutral-800 text-right">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-800">
                                <?php foreach($turnos as $t): ?>
                                    <tr class="hover:bg-neutral-800/50 transition duration-150 group">
                                        <td class="py-4 px-6 font-medium text-white">
                                            <?php echo date("d/m/Y", strtotime($t['fecha'])); ?>
                                        </td>
                                        <td class="py-4 px-6 text-neutral-400">
                                            <?php echo substr($t['hora'], 0, 5); ?> hs
                                        </td>
                                        
                                        <td class="py-4 px-6">
                                            <?php 
                                            if($t['estado'] == 'confirmado') {
                                                echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-900/30 text-emerald-400 border border-emerald-800">Confirmado</span>';
                                            } elseif($t['estado'] == 'cancelado' || $t['estado'] == 'cancelado_cliente') {
                                                echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-900/30 text-rose-400 border border-rose-800">Cancelado</span>';
                                            } else {
                                                echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-900/30 text-amber-400 border border-amber-800">Pendiente</span>';
                                            }
                                            ?>
                                        </td>

                                        <td class="py-4 px-6 text-right">
                                            <?php if($t['estado'] != 'cancelado' && $t['estado'] != 'cancelado_cliente'): ?>
                                                <button onclick="cancelarTurno(<?php echo $t['id']; ?>)" 
                                                        class="text-rose-500 hover:text-rose-300 text-xs font-bold border border-rose-900 hover:bg-rose-900/20 px-3 py-1.5 rounded-lg transition">
                                                    Cancelar
                                                </button>
                                            <?php else: ?>
                                                <span class="text-xs text-neutral-600">—</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="p-12 text-center">
                        <div class="inline-block p-4 rounded-full bg-neutral-800 mb-4 border border-neutral-700">
                            <svg class="w-8 h-8 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-neutral-400 text-sm">Aún no tienes turnos reservados.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </main>

</body>
</html>