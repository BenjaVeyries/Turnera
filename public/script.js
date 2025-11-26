// --- CONFIGURACIÓN E INTERFAZ ---

document.addEventListener('DOMContentLoaded', () => {

    cargarConfiguracion();

    try {
        const swiper = new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            grabCursor: true, // MEJORA: Cursor de manito en PC
            observer: true,   // MEJORA: Detecta cambios en el DOM
            observeParents: true, // MEJORA: Detecta cambios en contenedores padres
            
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            },
            breakpoints: {
                640: { slidesPerView: 2, spaceBetween: 20 },
                1024: { slidesPerView: 3, spaceBetween: 30 },
            },
        });
    } catch(e) { console.log("Swiper no cargado en esta página"); }

    checkLoginStatus();

    if(document.getElementById('staff-container')) {
        cargarStaff();
    }
});

// Scroll suave
function scrollToSection(id) {
    const section = document.getElementById(id);
    if (section) {
        window.scrollTo({
            top: section.offsetTop - 80, 
            behavior: "smooth"
        });
    }
}

// --- LOGIC DE USUARIO ---
function checkLoginStatus() {
    const logueado = !!sessionStorage.getItem('usuario_id');
    const btnLogin = document.getElementById('btnLogin');
    const btnLogout = document.getElementById('btnLogout');

    if (logueado) {
        if(btnLogin) btnLogin.style.display = "none";
        if(btnLogout) btnLogout.style.display = "inline-block";
    } else {
        if(btnLogin) btnLogin.style.display = "inline-block";
        if(btnLogout) btnLogout.style.display = "none";
    }
}

function irReservar() {
    const usuario = sessionStorage.getItem('usuario_id');
    
    if (!usuario) {
        Swal.fire({
            title: 'Inicia Sesión',
            text: "Para reservar tu experiencia, necesitamos saber quién eres.",
            icon: 'info',
            background: '#171717',
            color: '#fff',
            confirmButtonColor: '#d97706',
            showCancelButton: true,
            confirmButtonText: 'Ir al Login',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) window.location.href = "login.html";
        });
    } else {
        window.location.href = "../controllers/ClienteController.php";
    }
}

function logout() {
    sessionStorage.removeItem('usuario_id');
    window.location.href = "../controllers/auth_logout.php";
}

// --- CARGA DINÁMICA DE PROFESIONALES ---
async function cargarStaff() {
    const contenedor = document.getElementById('staff-container');
    
    try {
        const res = await fetch('../controllers/PeluqueroController.php');
        const peluqueros = await res.json();
        
        contenedor.innerHTML = ''; 

        if(peluqueros.length === 0) {
            contenedor.innerHTML = '<p class="text-neutral-500 col-span-full text-center">No hay profesionales disponibles.</p>';
            return;
        }

        const fragment = document.createDocumentFragment();

        peluqueros.forEach(p => {
            const fotoUrl = p.foto && p.foto !== '' 
                ? `../public/uploads/${p.foto}` 
                : 'https://cdn-icons-png.flaticon.com/512/147/147144.png';

            const div = document.createElement('div');
            div.className = "group bg-neutral-900 rounded-xl overflow-hidden border border-neutral-800 hover:border-amber-600/50 transition-colors duration-300";
            
            div.innerHTML = `
                <div class="h-64 overflow-hidden relative bg-neutral-800">
                    <img src="${fotoUrl}" 
                         loading="lazy" 
                         class="w-full h-full object-cover grayscale group-hover:grayscale-0 scale-100 group-hover:scale-105 transition-all duration-300 ease-out transform-gpu opacity-0" 
                         alt="${p.nombre}"
                         onload="this.classList.remove('opacity-0')"
                         onerror="this.src='https://cdn-icons-png.flaticon.com/512/147/147144.png'">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-neutral-900 to-transparent opacity-60 pointer-events-none"></div>
                </div>
                
                <div class="p-6 relative -mt-12 z-10">
                    <div class="bg-neutral-950 p-4 rounded-lg shadow-xl border border-white/5 text-center transform transition-transform duration-300 group-hover:-translate-y-1">
                        <h3 class="text-xl font-bold text-white font-serif tracking-wide">${p.nombre}</h3>
                        <p class="text-amber-600 text-[10px] font-bold tracking-[0.2em] uppercase mt-2">Estilista Senior</p>
                        <p class="text-neutral-500 text-xs mt-3 line-clamp-2 px-2">${p.biografia || 'Especialista en cortes y cuidado masculino.'}</p>
                    </div>
                </div>
            `;
            fragment.appendChild(div);
        });

        contenedor.appendChild(fragment);

    } catch(e) {
        console.error("Error cargando staff:", e);
        contenedor.innerHTML = '<p class="text-red-500 col-span-full text-center">Error al cargar el equipo.</p>';
    }
}

// --- CARGAR CONFIGURACIÓN (MAPA) ---
async function cargarConfiguracion() {
    try {
        const res = await fetch('../controllers/PublicConfig.php');
        const data = await res.json();

        const iframe = document.getElementById('mapa-frame');
        if (iframe && data.mapa) {
            iframe.src = data.mapa;
        }
    } catch (error) {
        console.error("Error cargando config:", error);
    }
}