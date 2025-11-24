Sistema de Gestión de Turnos - Barbería
Trabajo Final - Programación III

👥 Integrantes del Grupo
Benjamín Veyries

Kevin Yañez

Lazaro Abecia

Mariano Young

📝 Descripción del Proyecto
Se ha desarrollado una aplicación web completa para la gestión de turnos de una barbería. El sistema permite a los clientes reservar citas mediante un flujo interactivo ("Wizard"), y ofrece paneles de administración diferenciados para Administradores y Profesionales (Peluqueros), permitiendo la gestión total del negocio, servicios y horarios.

🏗️ Arquitectura y Tecnologías
El proyecto fue refactorizado desde una estructura lineal a una arquitectura MVC (Modelo-Vista-Controlador) estricta para garantizar orden, escalabilidad y mantenimiento del código.

Lenguaje: PHP (Nativo).

Base de Datos: MySQL (Conexión centralizada vía PDO).

Frontend: HTML5, TailwindCSS, JavaScript (Fetch API para asincronía).

Librerías: SweetAlert2 (Alertas), FullCalendar (Visualización de agenda).

Estructura de Carpetas:
/models: Lógica de negocio y acceso a datos (SQL).

/views: Interfaz de usuario (HTML/PHP visual).

/controllers: Intermediarios que procesan peticiones y lógica de seguridad.

/config: Configuración de base de datos y sesiones.

/public: Recursos estáticos (JS, CSS, imágenes).

/auth: Lógica de protección de rutas.

🔒 Cumplimiento de Requisitos de Seguridad (PDF)
Siguiendo las especificaciones de "Incorporación de Login Seguro", hemos implementado:

Autenticación Robusta: Registro y Login con hash de contraseñas (password_hash / password_verify).

Protección de Sesiones:

Uso de session_regenerate_id(true) al iniciar sesión para evitar secuestro.

Configuración de cookies seguras con httponly y use_only_cookies.

Protección CSRF: Implementación de Tokens anti-CSRF en todos los formularios y peticiones fetch (Login, Reserva, ABM Admin).

Control de Roles: Sistema de permisos estricto (Administrador, Peluquero, Cliente) que redirige y protege accesos indebidos a controladores.

Rate Limiting: Bloqueo temporal de cuenta (10 minutos) tras 5 intentos fallidos de inicio de sesión.

Seguridad SQL: Uso total de sentencias preparadas (PDO Prepared Statements) para prevenir inyección SQL.

⭐ Funcionalidades Destacadas (Valor Agregado)
Además de lo solicitado, hemos incorporado características avanzadas para mejorar la experiencia de uso:

Reserva "Wizard" Paso a Paso: El cliente selecciona Servicio → Profesional → Fecha/Hora disponible → Confirmación.

Lógica de Horarios Dinámica: El sistema calcula los turnos disponibles basándose en la duración del servicio y el horario específico de cada peluquero (soporte para turnos cortados).

Gestión de Profesionales: El Admin puede crear peluqueros, subir su foto de perfil, asignarles servicios específicos y definir sus horarios laborales.

Notificaciones In-App: Sistema de campanita 🔔 que avisa al Admin/Peluquero cuando hay nuevas reservas y al Cliente cuando su turno cambia de estado.

Integración WhatsApp: Al confirmar o cancelar un turno, el sistema genera un enlace directo para enviar un mensaje predefinido al cliente por WhatsApp Web.

🚀 Instrucciones de Instalación Rápida
Base de Datos:

Importar el archivo barberia.sql en phpMyAdmin (o ejecutar los scripts de limpieza y carga de datos de prueba provistos).

Configuración:

Verificar credenciales en config/conexion_db.php (por defecto: root, sin contraseña).

Ejecución:

Iniciar Apache y MySQL en XAMPP.

Acceder desde el navegador a la carpeta del proyecto (ej: localhost/Turnera/).

El sistema redirigirá automáticamente mediante index.php.

Credenciales de Prueba (Demo Completa):

Admin: admin@barberia.com / 1234

Peluquero: juan@barberia.com / 1234

Cliente: carlos@gmail.com / 1234