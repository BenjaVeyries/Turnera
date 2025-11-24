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
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center mb-6">
            <span class="inline-block p-3 rounded-full bg-blue-100 text-blue-600 mb-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            </span>
            <h2 class="text-3xl font-extrabold text-slate-900">Nuevo Servicio</h2>
            <p class="mt-2 text-sm text-slate-600">Agrega opciones al menú de la barbería</p>
        </div>

        <div class="bg-white py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10 border border-slate-100">
            <form action="../controllers/AdminServicioController.php" method="POST" class="space-y-6">
                <input type="hidden" name="accion" value="crear_servicio">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nombre del Servicio</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-slate-400">✂️</span>
                        </div>
                        <input type="text" name="nombre" required 
                            class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-slate-300 rounded-lg py-2.5" 
                            placeholder="Ej: Corte Fade + Barba">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Descripción (Opcional)</label>
                    <textarea name="descripcion" rows="2" 
                        class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-slate-300 rounded-lg py-2.5 px-3" 
                        placeholder="Ej: Incluye lavado y perfilado con navaja..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Precio ($)</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-slate-500">$</span>
                            </div>
                            <input type="number" name="precio" required 
                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 sm:text-sm border-slate-300 rounded-lg py-2.5" 
                                placeholder="0.00">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Duración (min)</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-slate-400">⏱</span>
                            </div>
                            <input type="number" name="duracion" required value="30"
                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-9 sm:text-sm border-slate-300 rounded-lg py-2.5">
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-[1.02]">
                        Guardar Servicio
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-slate-500">O</span>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <a href="../controllers/AdminDashboard.php" class="font-medium text-blue-600 hover:text-blue-500 transition">
                        ← Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>