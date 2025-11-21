<?php
require_once '../auth/require_login.php';
require_once '../models/Turno.php';

// Pedimos los datos al modelo
$turnos = Turno::obtenerPorUsuario($_SESSION['usuario_id']);
$nombre = $_SESSION['nombre'];

// Cargamos la vista limpia
require '../views/cliente_turnos.php';
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservar Turno</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">


<header class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">Turnera Barbería</h1>
    <div class="flex items-center gap-4">
        <p class="font-semibold text-gray-600">Hola, <?php echo htmlspecialchars($nombre); ?></p>
        <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Cerrar sesión</a>
    </div>
</header>

<!-- MENSAJE INICIAL -->
<div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-xl shadow text-center">
    <h2 class="text-2xl font-bold mb-6">Bienvenido</h2>
    <p class="mb-4 text-gray-700">Inicio de sesión exitoso</p>
    <button id="btn-reserva" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Comenzar reserva</button>
</div>

<!-- FORMULARIO DE RESERVA -->
<div id="form-turno" class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-xl shadow mb-10" style="display: none;">
    <h2 class="text-2xl font-bold mb-6">Formulario de Turno</h2>
    <form id="reserva-form">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 font-semibold">Fecha</label>
                <input type="date" name="fecha" id="fecha-input" required class="w-full p-2 border rounded-lg">
            </div>
            <div>
                <label class="block mb-2 font-semibold">Hora</label>
                <select name="hora" id="hora-select" required class="w-full p-2 border rounded-lg">
                    <option value="">Seleccionar fecha primero</option>
                </select>
            </div>
        </div>
        <div class="mt-4 text-center">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">Reservar turno</button>
        </div>
    </form>
</div>

<!-- LISTA DE TURNOS -->
<div id="lista-turnos" class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow">
    <h2 class="text-2xl font-bold mb-6">Mis Turnos</h2>
    <div id="turnos-container">
    <?php if(!empty($turnos)): ?>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b"><th class="py-2">Fecha</th><th class="py-2">Hora</th></tr>
            </thead>
            <tbody>
            <?php foreach($turnos as $t): ?>
                <tr class="border-b hover:bg-gray-100">
                    <td class="py-2"><?php echo htmlspecialchars($t['fecha']); ?></td>
                    <td class="py-2"><?php echo htmlspecialchars($t['hora']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-gray-600">No tenés turnos reservados.</p>
    <?php endif; ?>
</div>
</div>

<script>
const btnReserva = document.getElementById('btn-reserva');
const formTurno = document.getElementById('form-turno');
const turnosContainer = document.getElementById('turnos-container');

btnReserva.addEventListener('click', ()=>{
    formTurno.style.display = 'block';
    formTurno.scrollIntoView({behavior:'smooth'});
});

const fechaInput = document.getElementById('fecha-input');
const horaSelect = document.getElementById('hora-select');

fechaInput.addEventListener('change', async ()=>{
    const fecha = fechaInput.value;
    if(!fecha) return;

    try {
        const res = await fetch('horas_disponibles.php?fecha='+fecha);
        const horas = await res.json();

        horaSelect.innerHTML = '';
        if(horas.length === 0){
            horaSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
        } else {
            horaSelect.innerHTML = '<option value="">Seleccionar hora</option>';
            horas.forEach(h => {
                const option = document.createElement('option');
                option.value = h;
                option.textContent = h;
                horaSelect.appendChild(option);
            });
        }
    } catch(err){
        console.error(err);
        horaSelect.innerHTML = '<option value="">Error cargando horarios</option>';
    }
});

document.getElementById('reserva-form').addEventListener('submit', async e => {
    e.preventDefault();

    const fecha = fechaInput.value;
    const hora = horaSelect.value;

    if(!fecha || !hora){
        Swal.fire('Error','Por favor completá todos los campos','error');
        return;
    }

    const formData = new FormData(e.target);

    try {
        const res = await fetch('guardar_turno.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if(data.status === 'ok'){
            Swal.fire('¡Turno reservado!','Tu turno ha sido registrado correctamente','success');

            const nuevaFila = `
                <tr class="border-b hover:bg-gray-100">
                    <td class="py-2">${fecha}</td>
                    <td class="py-2">${hora}</td>
                </tr>
            `;
            const table = turnosContainer.querySelector('table tbody');
            if(table){
                table.insertAdjacentHTML('beforeend', nuevaFila);
            } else {
                turnosContainer.innerHTML = `
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2">Fecha</th>
                                <th class="py-2">Hora</th>
                            </tr>
                        </thead>
                        <tbody>${nuevaFila}</tbody>
                    </table>
                `;
            }

            e.target.reset();
            horaSelect.innerHTML = '<option value="">Seleccionar fecha primero</option>';
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    } catch(err) {
        Swal.fire('Error','Ocurrió un problema. Intenta nuevamente','error');
    }
});
</script>

</body>
</html>







