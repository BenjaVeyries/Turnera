// public/turnos.js

// Función auxiliar para volver al inicio manteniendo sesión si existe
function volverInicio(){
    try{
        // Si tienes alguna lógica global de ID, va aquí
    }catch(e){
        console.warn('Error sesión', e);
    }
    window.location.href = '../views/index.html'; // Ajustado ruta relativa
}

// --- VARIABLES GLOBALES DEL WIZARD ---
let reserva = {
    servicio_id: null,
    servicio_nombre: '',
    servicio_precio: 0,
    peluquero_id: null,
    peluquero_nombre: '',
    fecha: null,
    hora: null
};

// Variable para la instancia del calendario
let calendarioInstance = null;

// 1. INICIAR WIZARD
const btnComenzar = document.getElementById('btn-reserva');
const wizardContainer = document.getElementById('wizard-reserva');

if(btnComenzar){
    btnComenzar.addEventListener('click', async () => {
        wizardContainer.style.display = 'block';
        wizardContainer.scrollIntoView({behavior:'smooth'});
        await cargarServicios(); // Carga el paso 1
        mostrarPaso(1);
    });
}

// --- NAVEGACIÓN ENTRE PASOS ---
function mostrarPaso(n) {
    // Ocultar todos
    document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
    // Mostrar el actual
    document.getElementById(`step-${n}`).classList.remove('hidden');
    
    // Actualizar barra superior (color azul)
    for(let i=1; i<=4; i++){
        const label = document.getElementById(`progreso-${i}`);
        if(i === n) label.classList.add('text-blue-600', 'font-bold');
        else label.classList.remove('text-blue-600', 'font-bold');
    }
}

function volverPaso(n) {
    mostrarPaso(n);
}

// --- PASO 1: SERVICIOS ---
async function cargarServicios() {
    const contenedor = document.getElementById('lista-servicios');
    contenedor.innerHTML = '<p>Cargando...</p>';
    
    try {
        const res = await fetch('../controllers/ServicioController.php');
        const servicios = await res.json();
        
        contenedor.innerHTML = '';
        servicios.forEach(s => {
            const div = document.createElement('div');
            div.className = "border p-4 rounded hover:border-blue-500 cursor-pointer transition hover:shadow-md bg-gray-50";
            div.innerHTML = `
                <h4 class="font-bold text-lg">${s.nombre}</h4>
                <p class="text-sm text-gray-600">${s.descripcion || ''}</p>
                <p class="text-blue-600 font-bold mt-2">$${s.precio}</p>
                <p class="text-xs text-gray-400">Duración: ${s.duracion_estimada} min</p>
            `;
            div.onclick = () => seleccionarServicio(s);
            contenedor.appendChild(div);
        });
    } catch(e) { contenedor.innerHTML = 'Error cargando servicios'; }
}

function seleccionarServicio(s) {
    reserva.servicio_id = s.id;
    reserva.servicio_nombre = s.nombre;
    reserva.servicio_precio = s.precio;
    cargarPeluqueros(s.id);
    mostrarPaso(2);
}

// --- PASO 2: PELUQUEROS ---
async function cargarPeluqueros(servicioId) {
    const contenedor = document.getElementById('lista-peluqueros');
    contenedor.innerHTML = '<p>Buscando profesionales...</p>';
    
    try {
        const res = await fetch(`../controllers/PeluqueroController.php?servicio_id=${servicioId}`);
        const peluqueros = await res.json();
        
        contenedor.innerHTML = '';
        if(peluqueros.length === 0) {
            contenedor.innerHTML = '<p class="col-span-3 text-center text-red-500">No hay peluqueros disponibles para este servicio.</p>';
            return;
        }

        peluqueros.forEach(p => {
            const div = document.createElement('div');
            div.className = "border p-4 rounded hover:border-blue-500 cursor-pointer text-center transition hover:shadow-md bg-white";
            // Usamos una imagen por defecto si no tiene foto
            const fotoUrl = p.foto 
        ? `../public/uploads/${p.foto}` 
        : 'https://cdn-icons-png.flaticon.com/512/147/147144.png';
            
            div.innerHTML = `
                <img src="${fotoUrl}" class="w-20 h-20 rounded-full mx-auto mb-2 object-cover">
                <h4 class="font-bold">${p.nombre}</h4>
                <p class="text-xs text-gray-500">${p.biografia || 'Estilista'}</p>
            `;
            div.onclick = () => seleccionarPeluquero(p);
            contenedor.appendChild(div);
        });
    } catch(e) { contenedor.innerHTML = 'Error cargando peluqueros'; }
}

function seleccionarPeluquero(p) {
    reserva.peluquero_id = p.id;
    reserva.peluquero_nombre = p.nombre;
    
    // Resetear fecha al cambiar de peluquero
    document.getElementById('wizard-fecha').value = '';
    
    // MODIFICACIÓN: Limpiamos la selección visual del calendario si existe
    if(calendarioInstance) {
        calendarioInstance.clear();
    }
    
    document.getElementById('grilla-horas').innerHTML = '<p class="col-span-3 text-gray-500 text-sm">Seleccioná una fecha en el calendario.</p>';
    mostrarPaso(3);
}

// --- PASO 3: HORARIOS (MODIFICADO CON FLATPICKR) ---

// Inicializamos el calendario cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    const mountPoint = document.getElementById('calendar-mount');
    
    // Solo inicializamos si existe el elemento (para evitar errores en otras páginas)
    if (mountPoint) {
        calendarioInstance = flatpickr(mountPoint, {
            inline: true,          // Calendario siempre visible (no popup)
            locale: "es",          // Idioma español
            minDate: "today",      // Bloquear fechas pasadas
            dateFormat: "Y-m-d",   // Formato compatible con PHP/MySQL
            disableMobile: true,   // Forzar vista de escritorio en móviles para mejor UX
            onChange: function(selectedDates, dateStr, instance) {
                // Cuando el usuario toca un día, llamamos a la función de carga
                onFechaSeleccionada(dateStr);
            }
        });
    }
});

// Nueva función para manejar la selección desde el calendario
async function onFechaSeleccionada(fecha) {
    if(!fecha) return;
    
    // Guardamos fecha en el objeto global y en el input hidden
    reserva.fecha = fecha;
    document.getElementById('wizard-fecha').value = fecha;

    const contenedor = document.getElementById('grilla-horas');
    contenedor.innerHTML = '<p class="col-span-full text-center text-amber-500 animate-pulse text-sm">Buscando disponibilidad...</p>';

    try {
        const res = await fetch(`../controllers/HorarioController.php?fecha=${fecha}&peluquero_id=${reserva.peluquero_id}`);
        const horas = await res.json();

        contenedor.innerHTML = '';
        if(horas.length === 0) {
            contenedor.innerHTML = `
                <div class="col-span-full text-center py-4 bg-neutral-950/50 rounded-lg border border-red-900/30">
                    <p class="text-red-400 font-bold text-sm">Sin turnos disponibles</p>
                    <p class="text-neutral-500 text-xs mt-1">Prueba otra fecha.</p>
                </div>`;
        } else {
            horas.forEach(h => {
                const btn = document.createElement('button');
                const horaCorta = h.substring(0, 5); 
                btn.textContent = horaCorta;
                // Estilos adaptados al modo oscuro
                btn.className = "bg-neutral-800 border border-neutral-700 text-neutral-300 py-2 rounded hover:bg-amber-600 hover:text-white hover:border-amber-500 transition font-medium text-sm";
                btn.onclick = () => seleccionarHora(h);
                contenedor.appendChild(btn);
            });
        }
    } catch(e) { 
        console.error(e);
        contenedor.innerHTML = '<p class="text-red-500 col-span-full text-center text-sm">Error al cargar horarios.</p>'; 
    }
}

function seleccionarHora(h) {
    reserva.hora = h;
    mostrarResumen();
    mostrarPaso(4);
}

// --- PASO 4: CONFIRMAR ---
function mostrarResumen() {
    document.getElementById('resumen-servicio').textContent = reserva.servicio_nombre;
    document.getElementById('resumen-peluquero').textContent = reserva.peluquero_nombre;
    document.getElementById('resumen-fecha').textContent = reserva.fecha;
    document.getElementById('resumen-hora').textContent = reserva.hora;
    document.getElementById('resumen-precio').textContent = reserva.servicio_precio;
}

const btnFinal = document.getElementById('btn-confirmar-final');
if(btnFinal) {
    btnFinal.addEventListener('click', async () => {
        // Enviar al backend
        const formData = new FormData();
        formData.append('fecha', reserva.fecha);
        formData.append('hora', reserva.hora);
        formData.append('servicio_id', reserva.servicio_id);
        formData.append('peluquero_id', reserva.peluquero_id);
        
        // IMPORTANTE: Agregar CSRF Token
        if(typeof CSRF_TOKEN !== 'undefined') {
            formData.append('csrf_token', CSRF_TOKEN);
        }

        try {
            const res = await fetch('../controllers/TurnoController.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if(data.status === 'ok') {
                Swal.fire('¡Reserva Exitosa!', 'Te esperamos en la barbería.', 'success')
                .then(() => location.reload());
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        } catch(e) {
            Swal.fire('Error', 'Fallo de conexión', 'error');
        }
    });
}

async function cancelarTurno(id) {
    const confirmacion = await Swal.fire({
        title: '¿Cancelar turno?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, cancelar'
    });

    if (confirmacion.isConfirmed) {
        try {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('csrf_token', CSRF_TOKEN);

            const res = await fetch('../controllers/TurnoCancelar.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if (data.status === 'ok') {
                await Swal.fire('Cancelado', 'Tu turno ha sido cancelado.', 'success');
                location.reload(); // Recargar para ver el cambio de estado
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Hubo un problema de conexión', 'error');
        }
    }
}