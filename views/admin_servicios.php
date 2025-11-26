<?php
require_once '../config/session_start.php';
require_once '../auth/require_login.php';
require_once '../models/Servicio.php';

// Seguridad Admin
if ($_SESSION['rol'] !== 'Administrador') {
    header("Location: ../controllers/auth_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Servicios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-neutral-950 text-neutral-300 min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 selection:bg-amber-500 selection:text-white">

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center mb-6">
            <span class="inline-block p-3 rounded-xl bg-neutral-900 border border-neutral-800 text-amber-500 mb-3 shadow-lg shadow-amber-900/10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            </span>
            <h2 class="text-3xl font-bold text-white tracking-tight">Nuevo Servicio</h2>
            <p class="mt-2 text-sm text-neutral-500">Agrega opciones exclusivas al menú</p>
        </div>

        <div class="bg-neutral-900 py-8 px-4 shadow-2xl sm:rounded-2xl sm:px-10 border border-neutral-800 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>

            <form action="../controllers/AdminServicioController.php" method="POST" class="space-y-6 relative z-10">
                <input type="hidden" name="accion" value="crear_servicio">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div>
                    <label class="block text-xs font-bold text-neutral-500 uppercase mb-1 tracking-wider">Nombre del Servicio</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-amber-600">✂️</span>
                        </div>
                        <input type="text" name="nombre" required 
                            class="block w-full pl-10 sm:text-sm bg-neutral-950 border-neutral-800 text-white rounded-lg py-2.5 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 outline-none transition placeholder-neutral-600" 
                            placeholder="Ej: Corte Fade + Barba">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-neutral-500 uppercase mb-1 tracking-wider">Descripción (Opcional)</label>
                    <textarea name="descripcion" rows="2" 
                        class="block w-full sm:text-sm bg-neutral-950 border-neutral-800 text-white rounded-lg py-2.5 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 outline-none transition placeholder-neutral-600" 
                        placeholder="Ej: Incluye lavado y perfilado con navaja..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-neutral-500 uppercase mb-1 tracking-wider">Precio ($)</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-neutral-500">$</span>
                            </div>
                            <input type="number" name="precio" required 
                                class="block w-full pl-7 sm:text-sm bg-neutral-950 border-neutral-800 text-white rounded-lg py-2.5 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 outline-none transition placeholder-neutral-600" 
                                placeholder="0.00">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-neutral-500 uppercase mb-1 tracking-wider">Duración (min)</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-neutral-500">⏱</span>
                            </div>
                            <input type="number" name="duracion" required value="30"
                                class="block w-full pl-9 sm:text-sm bg-neutral-950 border-neutral-800 text-white rounded-lg py-2.5 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 outline-none transition">
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-amber-900/20 text-sm font-bold text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all transform hover:scale-[1.02]">
                        Guardar Servicio
                    </button>
                </div>
            </form>

            <div class="mt-8">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-neutral-800"></div></div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-neutral-900 text-neutral-600">Navegación</span>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <a href="../controllers/AdminDashboard.php" class="font-medium text-amber-500 hover:text-amber-400 transition flex items-center justify-center gap-2 group">
                        <span class="group-hover:-translate-x-1 transition-transform">←</span> Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>