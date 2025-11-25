// Scroll suave
function scrollToSection(id) {
    const section = document.getElementById(id);
    if (section) {
        window.scrollTo({
            top: section.offsetTop - 60,
            behavior: "smooth"
        });
    }
}

// --- LOGIN / LOGOUT / RESERVA ---
window.addEventListener("DOMContentLoaded", () => {
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
});

// Redirige a turnos solo si está logueado
function irReservar() {
    const usuario = sessionStorage.getItem('usuario_id');
    
    if (!usuario) {
        // [MEJORA] Usamos SweetAlert2 en lugar del alert() nativo
        Swal.fire({
            title: '¡Ups!',
            text: "Debes iniciar sesión para reservar un turno.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6', // Azul Tailwind
            cancelButtonColor: '#ef4444', // Rojo Tailwind
            confirmButtonText: 'Iniciar Sesión',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "login.html";
            }
        });
        
    } else {
        // [CORRECCIÓN MVC] Redirige al Controlador ClienteController, no a turnos.php directo
        window.location.href = "../controllers/ClienteController.php";
    }
}

// Logout front-end (complementario al PHP)
function logout() {
    sessionStorage.removeItem('usuario_id');
    window.location.href = "../controllers/auth_logout.php";
}

// --- HEADER CAROUSEL (Mantenemos tu código original) ---
const carousel = document.getElementById("carousel");
if (carousel) { // Agregamos un chequeo de seguridad por si no existe el elemento en alguna página
    let index = 0;
    function nextSlide() {
        index = (index + 1) % 3; // cantidad de imágenes
        carousel.style.transform = `translateX(-${index * 100}%)`;
    }
    setInterval(nextSlide, 4000); // Cambia cada 4 segundos
}