// Turnos page JS (externalized)// Turnos page JS (moved from inline in turnos.php)






































































































}    });        }            Swal.fire('Error','Ocurrió un problema. Intenta nuevamente','error');        } catch(err) {            }                Swal.fire('Error', data.message, 'error');            } else {                horaSelect.innerHTML = '<option value="">Seleccionar fecha primero</option>';                e.target.reset();                }                    turnosContainer.innerHTML = `\n                    <table class="w-full text-left border-collapse">\n                        <thead>\n                            <tr class="border-b">\n                                <th class="py-2">Fecha</th>\n                                <th class="py-2">Hora</th>\n                            </tr>\n                        </thead>\n                        <tbody>${nuevaFila}</tbody>\n                    </table>\n                `;                } else {                    table.insertAdjacentHTML('beforeend', nuevaFila);                if(table){                const table = turnosContainer.querySelector('table tbody');                const nuevaFila = `\n                <tr class="border-b hover:bg-gray-100">\n                    <td class="py-2">${fecha}</td>\n                    <td class="py-2">${horaDisplay}</td>\n                </tr>\n            `;                const horaDisplay = hora.split(':').slice(0,2).join(':');                // Mostrar hora sin segundos en la tabla                Swal.fire('¡Turno reservado!','Tu turno ha sido registrado correctamente','success');            if(data.status === 'ok'){            const data = await res.json();            });                body: formData                method: 'POST',            const res = await fetch('guardar_turno.php', {        try {        const formData = new FormData(e.target);        }            return;            Swal.fire('Error','Por favor completá todos los campos','error');        if(!fecha || !hora){        const hora = horaSelect.value;        const fecha = fechaInput.value;        e.preventDefault();    reservaForm.addEventListener('submit', async e => {if(reservaForm){const reservaForm = document.getElementById('reserva-form');}    });        }            horaSelect.innerHTML = '<option value="">Error cargando horarios</option>';            console.error(err);        } catch(err){            }                });                    horaSelect.appendChild(option);                    option.textContent = h;                    option.value = h;                    const option = document.createElement('option');                horas.forEach(h => {                horaSelect.innerHTML = '<option value="">Seleccionar hora</option>';            } else {                horaSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';            if(!Array.isArray(horas) || horas.length === 0){            horaSelect.innerHTML = '';            const horas = await res.json();            const res = await fetch('horas_disponibles.php?fecha='+fecha);        try {        if(!fecha) return;        const fecha = fechaInput.value;    fechaInput.addEventListener('change', async ()=>{if(fechaInput){const horaSelect = document.getElementById('hora-select');const fechaInput = document.getElementById('fecha-input');}    });        formTurno.scrollIntoView({behavior:'smooth'});        formTurno.style.display = 'block';    btnReserva.addEventListener('click', ()=>{if(btnReserva){const turnosContainer = document.getElementById('turnos-container');const formTurno = document.getElementById('form-turno');const btnReserva = document.getElementById('btn-reserva');}    window.location.href = 'index.html';    }        console.warn('No se pudo establecer sessionStorage', e);    }catch(e){        }            sessionStorage.setItem('usuario_id', usuarioId);        if(usuarioId){        const usuarioId = window.USUARIO_ID;    try{function volverInicio(){
function volverInicio(){
    try{
        const usuarioId = window.USUARIO_ID;
        if(usuarioId){
            sessionStorage.setItem('usuario_id', usuarioId);
        }
    }catch(e){
        console.warn('No se pudo establecer sessionStorage', e);
    }
    window.location.href = 'index.html';
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
            const res = await fetch('horas_disponibles.php?fecha='+fecha);
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
            const res = await fetch('guardar_turno.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if(data.status === 'ok'){
                Swal.fire('¡Turno reservado!','Tu turno ha sido registrado correctamente','success');

                    const nuevaFila = `\n                <tr class="border-b hover:bg-neutral-700">\n                    <td class="py-2">${fecha}</td>\n                    <td class="py-2">${hora}</td>\n                </tr>\n            `;
                const table = turnosContainer.querySelector('table tbody');
                if(table){
                    table.insertAdjacentHTML('beforeend', nuevaFila);
                } else {
                    turnosContainer.innerHTML = `\n                    <table class="w-full text-left border-collapse">\n                        <thead>\n                            <tr class="border-b">\n                                <th class="py-2">Fecha</th>\n                                <th class="py-2">Hora</th>\n                            </tr>\n                        </thead>\n                        <tbody>${nuevaFila}</tbody>\n                    </table>\n                `;
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
}
