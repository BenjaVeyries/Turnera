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

const btnReserva = document.getElementById('btn-reserva');
const formTurno = document.getElementById('form-turno');
const turnosContainer = document.getElementById('turnos-container');

if(btnReserva){
    btnReserva.addEventListener('click', ()=>{
        formTurno.style.display = 'block';
        formTurno.scrollIntoView({behavior:'smooth'});
    });
}

const fechaInput = document.getElementById('fecha-input');
const horaSelect = document.getElementById('hora-select');

if(fechaInput){
    fechaInput.addEventListener('change', async ()=>{
        const fecha = fechaInput.value;
        if(!fecha) return;

        try {
            // CORRECCIÓN DE RUTA: Apunta a controllers
            const res = await fetch('../controllers/HorarioController.php?fecha='+fecha);
            const horas = await res.json();

            horaSelect.innerHTML = '';
            if(!Array.isArray(horas) || horas.length === 0){
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
}

const reservaForm = document.getElementById('reserva-form');
if(reservaForm){
    reservaForm.addEventListener('submit', async e => {
        e.preventDefault();

        const fecha = fechaInput.value;
        const hora = horaSelect.value;

        if(!fecha || !hora){
            Swal.fire('Error','Por favor completá todos los campos','error');
            return;
        }

        const formData = new FormData(e.target);

        try {
            // CORRECCIÓN DE RUTA: Apunta a controllers
            const res = await fetch('../controllers/TurnoController.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if(data.status === 'ok'){
                Swal.fire('¡Turno reservado!','Tu turno ha sido registrado correctamente','success');

                // Actualización visual de la tabla (opcional, ya que al recargar aparecerá)
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
                    // Si no había tabla antes, recargamos para que se cree la estructura
                    location.reload();
                }

                e.target.reset();
                horaSelect.innerHTML = '<option value="">Seleccionar fecha primero</option>';
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        } catch(err) {
            console.error(err);
            Swal.fire('Error','Ocurrió un problema. Intenta nuevamente','error');
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