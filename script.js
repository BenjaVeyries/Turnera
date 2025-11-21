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
    btnLogin.style.display = "none";
    btnLogout.style.display = "inline-block";
} 
else    {
    btnLogin.style.display = "inline-block";
    btnLogout.style.display = "none";
        }
});

// Redirige a turnos solo si está logueado
function irReservar() {
    const usuario = sessionStorage.getItem('usuario_id');
if (!usuario) {
    alert("Debes iniciar sesión para reservar un turno");
    window.location.href = "login.html";
} else  {
    window.location.href = "turnos.php";
        }
}

// Logout front-end (complementario al PHP)
function logout() {
    sessionStorage.removeItem('usuario_id');
    window.location.href = "logout.php";
}




