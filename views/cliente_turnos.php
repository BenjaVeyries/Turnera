<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Turno - Barbería</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        const CSRF_TOKEN = "<?php echo $_SESSION['csrf_token'] ?? ''; ?>";
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        }
    </script>
    <script src="../public/turnos.js" defer></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Animación suave para el wizard */
        .step-content { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    <header class="bg-slate-900 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <div class="flex items-center gap-2">
                <div class="bg-blue-600 p-1.5 rounded-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h1 class="text-lg font-bold tracking-wide">Turnera<span class="text-blue-400">App</span></h1>
            </div>

            <div class="flex items-center gap-6">
                
                <div class="relative group">
                    <button class="text-slate-300 hover:text-white focus:outline-none relative p-1 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <?php if(count($notificaciones) > 0): ?>
                            <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-slate-900"></span>
                        <?php endif; ?>
                    </button>

                    <div class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden z-50 hidden group-hover:block origin-top-right">
                        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Notificaciones</p>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            <?php if(count($notificaciones) > 0): ?>
                                <?php foreach($notificaciones as $noti): ?>
                                    <div class="px-4 py-3 border-b border-slate-50 hover:bg-blue-50 transition">
                                        <p class="text-sm text-slate-700"><?php echo htmlspecialchars($noti['mensaje']); ?></p>
                                        <p class="text-xs text-slate-400 mt-1"><?php echo $noti['creado_en']; ?></p>
                                    </div>
                                <?php endforeach; ?>
                                <a href="../controllers/marcar_leido.php" class="block w-full text-center text-xs text-blue-600 font-bold py-3 hover:bg-slate-50 bg-slate-50">Marcar leídas</a>
                            <?php else: ?>
                                <p class="px-4 py-6 text-sm text-slate-400 text-center">No tienes mensajes nuevos.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="hidden sm:flex items-center gap-3">
                    <p class="text-sm font-medium text-slate-200">Hola, <?php echo htmlspecialchars($nombre); ?></p>
                    <div class="h-4 w-px bg-slate-700"></div>
                    <a href="../controllers/auth_logout.php" class="text-sm text-red-400 hover:text-red-300 font-semibold transition">Salir</a>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-5xl mx-auto w-full p-6 space-y-10">

        <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-200 text-center md:text-left flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900">¡Bienvenido a tu espacio!</h2>
                <p class="text-slate-500 mt-2">Gestiona tus reservas o agendá un nuevo corte en pocos pasos.</p>
            </div>
            <button id="btn-reserva" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-600/20 transition transform hover:-translate-y-0.5 active:scale-95 flex items-center gap-2">
                <span>📅</span> Reservar Turno
            </button>
        </div>

        <div id="wizard-reserva" class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden" style="display: none;">
            
            <div class="bg-slate-50 px-8 py-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-bold text-slate-700">Nueva Reserva</h3>
                <button onclick="location.reload()" class="text-xs text-slate-400 hover:text-red-500 transition">✕ Cancelar</button>
            </div>

            <div class="px-8 pt-6">
                <div class="flex justify-between text-xs font-bold tracking-widest uppercase text-slate-400">
                    <span id="progreso-1" class="transition duration-300">1. Servicio</span>
                    <span id="progreso-2" class="transition duration-300">2. Profesional</span>
                    <span id="progreso-3" class="transition duration-300">3. Horario</span>
                    <span id="progreso-4" class="transition duration-300">4. Fin</span>
                </div>
                <div class="h-1 w-full bg-slate-100 mt-3 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 w-0 transition-all duration-500" id="barra-progreso"></div>
                </div>
            </div>

            <div class="p-8">
                <div id="step-1" class="step-content">
                    <h3 class="text-xl font-bold text-slate-800 mb-6 text-center">Elige tu estilo</h3>
                    <div id="lista-servicios" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <p class="text-slate-400 text-center col-span-2">Cargando servicios...</p>
                    </div>
                </div>

                <div id="step-2" class="step-content hidden">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-slate-800">Elige profesional</h3>
                        <button onclick="volverPaso(1)" class="text-sm text-blue-600 hover:underline">← Volver</button>
                    </div>
                    <div id="lista-peluqueros" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        </div>
                </div>

                <div id="step-3" class="step-content hidden">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-slate-800">¿Cuándo venís?</h3>
                        <button onclick="volverPaso(2)" class="text-sm text-blue-600 hover:underline">← Volver</button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-bold text-slate-500 mb-2 uppercase">Selecciona Fecha</label>
                            <input type="date" id="wizard-fecha" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-slate-700" min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-500 mb-2 uppercase">Horarios Disponibles</label>
                            <div id="grilla-horas" class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                <p class="col-span-full text-slate-400 text-sm italic bg-slate-50 p-4 rounded-lg text-center border border-dashed border-slate-200">
                                    👈 Selecciona una fecha para ver los turnos.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="step-4" class="step-content hidden">
                    <div class="text-center max-w-md mx-auto">
                        <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800 mb-2">Resumen de Reserva</h3>
                        <p class="text-slate-500 mb-8">Verifica que todo esté correcto antes de confirmar.</p>

                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 text-left space-y-3 mb-8">
                            <div class="flex justify-between border-b border-slate-200 pb-2">
                                <span class="text-slate-500">Servicio</span>
                                <span class="font-bold text-slate-800" id="resumen-servicio">-</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-200 pb-2">
                                <span class="text-slate-500">Profesional</span>
                                <span class="font-bold text-slate-800" id="resumen-peluquero">-</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-200 pb-2">
                                <span class="text-slate-500">Fecha</span>
                                <span class="font-bold text-slate-800" id="resumen-fecha">-</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-200 pb-2">
                                <span class="text-slate-500">Hora</span>
                                <span class="font-bold text-slate-800" id="resumen-hora">-</span>
                            </div>
                            <div class="flex justify-between pt-2 text-lg">
                                <span class="text-slate-900 font-bold">Total</span>
                                <span class="font-bold text-blue-600">$<span id="resumen-precio">0</span></span>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <button onclick="volverPaso(3)" class="w-1/3 py-3 border border-slate-300 rounded-xl text-slate-600 font-bold hover:bg-slate-50 transition">Atrás</button>
                            <button id="btn-confirmar-final" class="w-2/3 bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition transform active:scale-[0.98]">
                                Confirmar Reserva
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                📜 Mis Turnos
            </h3>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <?php if(!empty($turnos)): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-bold">
                                <tr>
                                    <th class="py-4 px-6">Fecha</th>
                                    <th class="py-4 px-6">Hora</th>
                                    <th class="py-4 px-6">Estado</th>
                                    <th class="py-4 px-6 text-right">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php foreach($turnos as $t): ?>
                                    <tr class="hover:bg-blue-50/50 transition duration-150">
                                        <td class="py-4 px-6 font-medium text-slate-700">
                                            <?php echo date("d/m/Y", strtotime($t['fecha'])); ?>
                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            <?php echo substr($t['hora'], 0, 5); ?> hs
                                        </td>
                                        
                                        <td class="py-4 px-6">
                                            <?php 
                                            if($t['estado'] == 'confirmado') {
                                                echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">Confirmado</span>';
                                            } elseif($t['estado'] == 'cancelado' || $t['estado'] == 'cancelado_cliente') {
                                                echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800">Cancelado</span>';
                                            } else {
                                                echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">Pendiente</span>';
                                            }
                                            ?>
                                        </td>

                                        <td class="py-4 px-6 text-right">
                                            <?php 
                                            // Lógica para mostrar botón cancelar
                                            if($t['estado'] != 'cancelado' && $t['estado'] != 'cancelado_cliente'): 
                                            ?>
                                                <button onclick="cancelarTurno(<?php echo $t['id']; ?>)" 
                                                        class="text-rose-500 hover:text-rose-700 text-xs font-bold border border-rose-200 hover:bg-rose-50 px-3 py-1.5 rounded-lg transition">
                                                    Cancelar
                                                </button>
                                            <?php else: ?>
                                                <span class="text-xs text-slate-300">—</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="p-10 text-center">
                        <div class="inline-block p-4 rounded-full bg-slate-50 mb-3">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-slate-500">Aún no tienes turnos reservados.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </main>

</body>
</html>