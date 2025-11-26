<?php
require_once '../config/session_start.php';
require_once '../auth/require_login.php';
require_once '../models/Servicio.php'; 

if ($_SESSION['rol'] !== 'Administrador') {
    header("Location: ../controllers/auth_login.php");
    exit;
}

$listaServicios = Servicio::obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Profesional</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Checkbox personalizado */
        .form-checkbox { border-radius: 0.25rem; }
        /* Scroll para la lista de servicios */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #171717; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #404040; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #d97706; }
    </style>
</head>
<body class="bg-neutral-950 text-neutral-300 min-h-screen p-6 selection:bg-amber-500 selection:text-white">

    <div class="max-w-6xl mx-auto">
        
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white tracking-tight">Nuevo Profesional</h1>
                <p class="text-neutral-500 mt-1">Da de alta a un nuevo miembro del equipo.</p>
            </div>
            <a href="../controllers/AdminDashboard.php" class="bg-neutral-900 border border-neutral-800 text-neutral-400 hover:bg-neutral-800 hover:text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition">
                Cancelar y Volver
            </a>
        </div>

        <form action="../controllers/AdminPeluqueroController.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-neutral-900 p-6 rounded-2xl shadow-sm border border-neutral-800">
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                            <span class="bg-neutral-800 text-amber-500 border border-neutral-700 p-1 rounded">👤</span> Datos Personales
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-neutral-500 uppercase mb-1">Nombre Completo</label>
                                <input type="text" name="nombre" required 
                                    class="w-full bg-neutral-950 border border-neutral-800 text-white rounded-lg focus:ring-1 focus:ring-amber-500 focus:border-amber-500 sm:text-sm p-2.5 outline-none transition placeholder-neutral-600">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-neutral-500 uppercase mb-1">Email</label>
                                <input type="email" name="email" required 
                                    class="w-full bg-neutral-950 border border-neutral-800 text-white rounded-lg focus:ring-1 focus:ring-amber-500 focus:border-amber-500 sm:text-sm p-2.5 outline-none transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-neutral-500 uppercase mb-1">Contraseña</label>
                                <input type="password" name="password" required 
                                    class="w-full bg-neutral-950 border border-neutral-800 text-white rounded-lg focus:ring-1 focus:ring-amber-500 focus:border-amber-500 sm:text-sm p-2.5 outline-none transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-neutral-500 uppercase mb-1">Teléfono</label>
                                <input type="text" name="telefono" 
                                    class="w-full bg-neutral-950 border border-neutral-800 text-white rounded-lg focus:ring-1 focus:ring-amber-500 focus:border-amber-500 sm:text-sm p-2.5 outline-none transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-neutral-500 uppercase mb-1">Biografía</label>
                                <textarea name="bio" rows="3" 
                                    class="w-full bg-neutral-950 border border-neutral-800 text-white rounded-lg focus:ring-1 focus:ring-amber-500 focus:border-amber-500 sm:text-sm p-2.5 outline-none transition"></textarea>
                            </div>

                            <div class="pt-2">
                                <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wide mb-2">Foto de Perfil</label>
                                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-neutral-700 rounded-xl cursor-pointer bg-neutral-950 hover:bg-neutral-800 hover:border-amber-500/50 transition group relative overflow-hidden" id="dropzone">
                                    
                                    <img id="preview-img" class="absolute inset-0 w-full h-full object-cover hidden opacity-80">

                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 relative z-10" id="upload-content">
                                        <svg class="w-8 h-8 mb-2 text-neutral-600 group-hover:text-amber-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p class="text-xs text-neutral-500 group-hover:text-amber-500 font-medium transition" id="file-name">Click para subir imagen</p>
                                    </div>
                                    
                                    <input type="file" name="foto" id="input-foto" accept="image/*" class="hidden" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-neutral-900 p-6 rounded-2xl shadow-sm border border-neutral-800">
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                            <span class="bg-neutral-800 text-amber-500 border border-neutral-700 p-1 rounded">✂️</span> Servicios Asignados
                        </h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 max-h-48 overflow-y-auto custom-scroll">
                            <?php if(count($listaServicios) > 0): ?>
                                <?php foreach($listaServicios as $s): ?>
                                    <label class="flex items-center p-3 border border-neutral-800 bg-neutral-950 rounded-lg cursor-pointer hover:border-amber-500/50 transition select-none group">
                                        <input type="checkbox" name="servicios[]" value="<?php echo $s['id']; ?>" class="w-4 h-4 text-amber-600 border-neutral-700 rounded focus:ring-amber-500 bg-neutral-900">
                                        <span class="ml-2 text-sm font-medium text-neutral-400 group-hover:text-white transition"><?php echo htmlspecialchars($s['nombre']); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="col-span-3 text-sm text-red-400 bg-red-900/20 p-3 rounded border border-red-800/50 text-center">⚠️ No hay servicios creados. Debes crear servicios antes de asignar.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="bg-neutral-900 p-6 rounded-2xl shadow-sm border border-neutral-800">
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                            <span class="bg-neutral-800 text-amber-500 border border-neutral-700 p-1 rounded">📅</span> Disponibilidad Semanal
                        </h3>
                        
                        <div class="space-y-1">
                            <?php 
                            $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
                            foreach($dias as $dia): 
                            ?>
                            <div class="flex flex-wrap md:flex-nowrap items-center gap-4 p-3 rounded-lg hover:bg-neutral-800 border border-transparent hover:border-neutral-700 transition">
                                <div class="w-32 shrink-0 flex items-center">
                                    <input type="checkbox" name="dias_activos[<?php echo $dia; ?>]" value="1" class="w-5 h-5 text-amber-600 border-neutral-600 bg-neutral-900 rounded focus:ring-amber-500 toggle-dia cursor-pointer" data-dia="<?php echo $dia; ?>">
                                    <span class="ml-2 font-semibold text-neutral-300"><?php echo $dia; ?></span>
                                </div>

                                <div id="horarios-<?php echo $dia; ?>" class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 opacity-40 pointer-events-none transition-all duration-200">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-neutral-600 font-bold uppercase w-8">T1</span>
                                        <input type="time" name="horarios[<?php echo $dia; ?>][0][inicio]" value="09:00" class="p-1.5 bg-neutral-950 border border-neutral-800 rounded text-sm w-full text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none" disabled>
                                        <span class="text-neutral-600">-</span>
                                        <input type="time" name="horarios[<?php echo $dia; ?>][0][fin]" value="13:00" class="p-1.5 bg-neutral-950 border border-neutral-800 rounded text-sm w-full text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none" disabled>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-neutral-600 font-bold uppercase w-8">T2</span>
                                        <input type="time" name="horarios[<?php echo $dia; ?>][1][inicio]" class="p-1.5 bg-neutral-950 border border-neutral-800 rounded text-sm w-full text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none" disabled>
                                        <span class="text-neutral-600">-</span>
                                        <input type="time" name="horarios[<?php echo $dia; ?>][1][fin]" class="p-1.5 bg-neutral-950 border border-neutral-800 rounded text-sm w-full text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none" disabled>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-amber-900/20 transition transform active:scale-[0.99] text-lg">
                        Guardar Profesional
                    </button>

                </div>
            </div>
        </form>
    </div>

    <script>
        const inputFoto = document.getElementById('input-foto');
        const previewImg = document.getElementById('preview-img');
        const uploadContent = document.getElementById('upload-content');
        const fileName = document.getElementById('file-name');
        
        inputFoto.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Mostrar nombre
                fileName.textContent = file.name;
                fileName.classList.add('text-amber-500', 'font-bold');
                
                // Mostrar vista previa
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                    // Ocultamos el icono para que se vea la foto limpia (con fondo oscuro semi-transparente al hover)
                    uploadContent.className = "flex flex-col items-center justify-center pt-5 pb-6 relative z-10 opacity-0 hover:opacity-100 transition-opacity bg-neutral-900/80 w-full h-full absolute";
                }
                reader.readAsDataURL(file);
            }
        });

        document.querySelectorAll('.toggle-dia').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const dia = this.dataset.dia;
                const contenedor = document.getElementById('horarios-' + dia);
                const inputs = contenedor.querySelectorAll('input');
                
                if(this.checked) {
                    contenedor.classList.remove('opacity-40', 'pointer-events-none');
                    inputs.forEach(i => i.disabled = false);
                } else {
                    contenedor.classList.add('opacity-40', 'pointer-events-none');
                    inputs.forEach(i => i.disabled = true);
                }
            });
        });
    </script>
</body>
</html>