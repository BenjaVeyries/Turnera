<?php
// views/admin_crear_peluquero.php
session_start();
require_once '../auth/require_login.php';
require_once '../models/Servicio.php'; // Necesitamos cargar los servicios

// Seguridad Admin
if ($_SESSION['rol'] !== 'Administrador') {
    header("Location: ../controllers/auth_login.php");
    exit;
}

// Obtenemos los servicios disponibles para mostrarlos
$listaServicios = Servicio::obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Profesional</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-200">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-800">✂️ Nuevo Profesional Completo</h2>
            <a href="../controllers/AdminDashboard.php" class="text-gray-500 hover:text-gray-700 text-sm">← Volver</a>
        </div>
        
        <form action="../controllers/AdminPeluqueroController.php" method="POST" enctype="multipart/form-data">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-bold text-lg mb-3 text-blue-600">1. Datos Personales</h3>
                    
                    <div class="mb-3">
                        <label class="block text-sm font-bold text-gray-700">Nombre Completo</label>
                        <input type="text" name="nombre" required class="w-full border p-2 rounded">
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-bold text-gray-700">Email</label>
                        <input type="email" name="email" required class="w-full border p-2 rounded">
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-bold text-gray-700">Contraseña</label>
                        <input type="password" name="password" required class="w-full border p-2 rounded">
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-bold text-gray-700">Teléfono</label>
                        <input type="text" name="telefono" class="w-full border p-2 rounded">
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-bold text-gray-700">Foto</label>
                        <input type="file" name="foto" accept="image/*" class="w-full border p-2 rounded text-sm">
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-bold text-gray-700">Biografía</label>
                        <textarea name="bio" rows="2" class="w-full border p-2 rounded"></textarea>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-lg mb-3 text-blue-600">2. Servicios que realiza</h3>
                    <div class="bg-gray-50 p-3 rounded border h-40 overflow-y-auto mb-6">
                        <?php if(count($listaServicios) > 0): ?>
                            <?php foreach($listaServicios as $s): ?>
                                <label class="flex items-center space-x-2 mb-2 cursor-pointer">
                                    <input type="checkbox" name="servicios[]" value="<?php echo $s['id']; ?>" class="w-4 h-4 text-blue-600">
                                    <span class="text-sm text-gray-700"><?php echo htmlspecialchars($s['nombre']); ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-red-500 text-xs">No hay servicios creados. Ve a "Servicios" primero.</p>
                        <?php endif; ?>
                    </div>

                    <h3 class="font-bold text-lg mb-3 text-blue-600">3. Disponibilidad Semanal (Doble Turno)</h3>
                        <div class="space-y-4 bg-gray-50 p-4 rounded border">
                            <?php 
                            $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
                            foreach($dias as $dia): 
                            ?>
                            <div class="border-b pb-2 last:border-0">
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" name="dias_activos[<?php echo $dia; ?>]" value="1" class="mr-2 w-5 h-5 toggle-dia text-blue-600" data-dia="<?php echo $dia; ?>">
                                    <span class="font-bold text-gray-700"><?php echo $dia; ?></span>
                                </div>

                                <div id="horarios-<?php echo $dia; ?>" class="opacity-50 ml-6 space-y-2 transition-opacity duration-300">
                                    
                                    <div class="flex items-center gap-2 text-sm">
                                        <span class="text-gray-500 w-16">Turno 1:</span>
                                        <input type="time" name="horarios[<?php echo $dia; ?>][0][inicio]" value="09:00" class="border rounded p-1" disabled>
                                        <span>a</span>
                                        <input type="time" name="horarios[<?php echo $dia; ?>][0][fin]" value="13:00" class="border rounded p-1" disabled>
                                    </div>

                                    <div class="flex items-center gap-2 text-sm">
                                        <span class="text-gray-500 w-16">Turno 2:</span>
                                        <input type="time" name="horarios[<?php echo $dia; ?>][1][inicio]" class="border rounded p-1" disabled>
                                        <span>a</span>
                                        <input type="time" name="horarios[<?php echo $dia; ?>][1][fin]" class="border rounded p-1" disabled>
                                        <span class="text-xs text-gray-400">(Opcional)</span>
                                    </div>

                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 font-bold text-lg shadow-md transition">
                💾 Guardar Profesional Completo
            </button>
        </form>
    </div>

    <script>
    document.querySelectorAll('.toggle-dia').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const dia = this.dataset.dia;
            const contenedor = document.getElementById('horarios-' + dia);
            const inputs = contenedor.querySelectorAll('input');
            
            if(this.checked) {
                contenedor.classList.remove('opacity-50');
                inputs.forEach(i => i.disabled = false);
            } else {
                contenedor.classList.add('opacity-50');
                inputs.forEach(i => i.disabled = true);
            }
        });
    });
</script>
</body>
</html>