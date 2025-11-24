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
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen p-6">

    <div class="max-w-6xl mx-auto">
        
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Nuevo Profesional</h1>
                <p class="text-slate-500 mt-1">Da de alta a un nuevo miembro del equipo.</p>
            </div>
            <a href="../controllers/AdminDashboard.php" class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition">
                Cancelar y Volver
            </a>
        </div>

        <form action="../controllers/AdminPeluqueroController.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <span class="bg-purple-100 text-purple-600 p-1 rounded">👤</span> Datos Personales
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nombre Completo</label>
                                <input type="text" name="nombre" required class="w-full border-slate-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 sm:text-sm p-2.5 border">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Email</label>
                                <input type="email" name="email" required class="w-full border-slate-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 sm:text-sm p-2.5 border">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Contraseña</label>
                                <input type="password" name="password" required class="w-full border-slate-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 sm:text-sm p-2.5 border">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Teléfono</label>
                                <input type="text" name="telefono" class="w-full border-slate-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 sm:text-sm p-2.5 border">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Biografía</label>
                                <textarea name="bio" rows="3" class="w-full border-slate-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 sm:text-sm p-2.5 border"></textarea>
                            </div>

                            <div class="pt-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Foto de Perfil</label>
                                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-300 rounded-xl cursor-pointer bg-slate-50 hover:bg-purple-50 hover:border-purple-300 transition group relative overflow-hidden" id="dropzone">
                                    
                                    <img id="preview-img" class="absolute inset-0 w-full h-full object-cover hidden opacity-80">

                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 relative z-10" id="upload-content">
                                        <svg class="w-8 h-8 mb-2 text-slate-400 group-hover:text-purple-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p class="text-xs text-slate-500 group-hover:text-purple-600 font-medium" id="file-name">Click para subir imagen</p>
                                    </div>
                                    
                                    <input type="file" name="foto" id="input-foto" accept="image/*" class="hidden" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <span class="bg-blue-100 text-blue-600 p-1 rounded">✂️</span> Servicios Asignados
                        </h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 max-h-48 overflow-y-auto custom-scroll">
                            <?php if(count($listaServicios) > 0): ?>
                                <?php foreach($listaServicios as $s): ?>
                                    <label class="flex items-center p-3 border border-slate-200 rounded-lg cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition select-none">
                                        <input type="checkbox" name="servicios[]" value="<?php echo $s['id']; ?>" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm font-medium text-slate-700"><?php echo htmlspecialchars($s['nombre']); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="col-span-3 text-sm text-red-500 bg-red-50 p-3 rounded border border-red-200 text-center">⚠️ No hay servicios creados. Debes crear servicios antes de asignar.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <span class="bg-green-100 text-green-600 p-1 rounded">📅</span> Disponibilidad Semanal
                        </h3>
                        
                        <div class="space-y-1">
                            <?php 
                            $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
                            foreach($dias as $dia): 
                            ?>
                            <div class="flex flex-wrap md:flex-nowrap items-center gap-4 p-3 rounded-lg hover:bg-slate-50 border border-transparent hover:border-slate-100 transition">
                                <div class="w-32 shrink-0 flex items-center">
                                    <input type="checkbox" name="dias_activos[<?php echo $dia; ?>]" value="1" class="w-5 h-5 text-green-600 border-slate-300 rounded focus:ring-green-500 toggle-dia cursor-pointer" data-dia="<?php echo $dia; ?>">
                                    <span class="ml-2 font-semibold text-slate-700"><?php echo $dia; ?></span>
                                </div>

                                <div id="horarios-<?php echo $dia; ?>" class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 opacity-40 pointer-events-none transition-all duration-200">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-slate-400 font-bold uppercase w-8">T1</span>
                                        <input type="time" name="horarios[<?php echo $dia; ?>][0][inicio]" value="09:00" class="p-1.5 border border-slate-200 rounded text-sm w-full focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none" disabled>
                                        <span class="text-slate-300">-</span>
                                        <input type="time" name="horarios[<?php echo $dia; ?>][0][fin]" value="13:00" class="p-1.5 border border-slate-200 rounded text-sm w-full focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none" disabled>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-slate-400 font-bold uppercase w-8">T2</span>
                                        <input type="time" name="horarios[<?php echo $dia; ?>][1][inicio]" class="p-1.5 border border-slate-200 rounded text-sm w-full focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none" disabled>
                                        <span class="text-slate-300">-</span>
                                        <input type="time" name="horarios[<?php echo $dia; ?>][1][fin]" class="p-1.5 border border-slate-200 rounded text-sm w-full focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none" disabled>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-purple-600/20 transition transform active:scale-[0.99] text-lg">
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
                fileName.classList.add('text-purple-700', 'font-bold');
                
                // Mostrar vista previa
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                    // Ocultamos el icono para que se vea la foto limpia
                    uploadContent.classList.add('opacity-0', 'hover:opacity-100', 'transition-opacity', 'bg-white/80', 'w-full', 'h-full', 'absolute'); 
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