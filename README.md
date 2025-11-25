# Sistema de Gestión de Turnos - Barbería
**Trabajo Final - Programación III**

## 👥 Integrantes del Grupo
* Benjamín Veyries
* Kevin Yañez
* Lazaro Abecia
* Mariano Young

## 📝 Descripción del Proyecto
Se ha desarrollado una aplicación web completa para la gestión de turnos de una barbería. El sistema permite a los clientes reservar citas mediante un flujo interactivo ("Wizard"), y ofrece paneles de administración diferenciados para Administradores y Profesionales (Peluqueros), permitiendo la gestión total del negocio, servicios y horarios.

## 🏗️ Arquitectura y Tecnologías
El proyecto fue refactorizado desde una estructura lineal a una arquitectura **MVC (Modelo-Vista-Controlador)** estricta para garantizar orden, escalabilidad y mantenimiento del código.

* **Lenguaje:** PHP (Nativo).
* **Base de Datos:** MySQL (Conexión centralizada vía PDO).
* **Gestión de Dependencias:** Composer.
* **Frontend:** HTML5, TailwindCSS, JavaScript (Fetch API para asincronía).
* **Librerías:**
    * `vlucas/phpdotenv` (Manejo de variables de entorno).
    * `SweetAlert2` (Alertas interactivas).
    * `FullCalendar` (Visualización de agenda).

### Estructura de Carpetas:
* `/models`: Lógica de negocio y acceso a datos (SQL).
* `/views`: Interfaz de usuario (HTML/PHP visual).
* `/controllers`: Intermediarios que procesan peticiones y lógica de seguridad.
* `/config`: Configuración de base de datos y sesiones.
* `/public`: Recursos estáticos (JS, CSS, imágenes).
* `/auth`: Lógica de protección de rutas.
* `/vendor`: Librerías de terceros (Composer).

## 🔒 Cumplimiento de Requisitos de Seguridad
Siguiendo las especificaciones de "Incorporación de Login Seguro", hemos implementado:

* **Autenticación Robusta:** Registro y Login con hash de contraseñas (`password_hash` / `password_verify`).
* **Protección de Sesiones:**
    * Uso de `session_regenerate_id(true)` al iniciar sesión para evitar secuestro.
    * Configuración de cookies seguras con `httponly` y `use_only_cookies`.
* **Protección CSRF:** Implementación de Tokens anti-CSRF en todos los formularios y peticiones fetch (Login, Reserva, ABM Admin).
* **Control de Roles:** Sistema de permisos estricto (Administrador, Peluquero, Cliente) que redirige y protege accesos indebidos a controladores.
* **Rate Limiting:** Bloqueo temporal de cuenta (10 minutos) tras 5 intentos fallidos de inicio de sesión.
* **Seguridad SQL:** Uso total de sentencias preparadas (`PDO Prepared Statements`) para prevenir inyección SQL.
* **Seguridad de Credenciales:** Uso de variables de entorno (`.env`) para evitar exponer credenciales de base de datos en el código fuente.

## ⭐ Funcionalidades Destacadas (Valor Agregado)
Además de lo solicitado, hemos incorporado características avanzadas para mejorar la experiencia de uso:

* **Reserva "Wizard" Paso a Paso:** El cliente selecciona Servicio → Profesional → Fecha/Hora disponible → Confirmación.
* **Lógica de Horarios Dinámica:** El sistema calcula los turnos disponibles basándose en la duración del servicio y el horario específico de cada peluquero (soporte para turnos cortados).
* **Gestión de Profesionales:** El Admin puede crear peluqueros, subir su foto de perfil, asignarles servicios específicos y definir sus horarios laborales.
* **Notificaciones In-App:** Sistema de campanita 🔔 que avisa al Admin/Peluquero cuando hay nuevas reservas y al Cliente cuando su turno cambia de estado.
* **Integración WhatsApp:** Al confirmar o cancelar un turno, el sistema genera un enlace directo para enviar un mensaje predefinido al cliente por WhatsApp Web.

---

## 🚀 Instrucciones de Instalación y Despliegue

Siga estos pasos para levantar el entorno de desarrollo localmente:

### 1. Base de Datos
Importar el archivo `barberia.sql` (ubicado en la carpeta `/base-de-datos`) en su gestor MySQL local (ej: phpMyAdmin).

### 2. Instalación de Dependencias
Este proyecto utiliza **Composer** para gestionar librerías de terceros. Abra una terminal en la raíz del proyecto y ejecute:

```bash
composer install
```

### 3. Configuración de Entorno (.env)
Para mantener la seguridad de las credenciales, el sistema no incluye claves en el código.
Debe crear un archivo llamado `.env` en la raíz del proyecto y definir las siguientes variables según su configuración local:

```ini
DB_HOST=localhost
DB_NAME=barberia
DB_USER=root
DB_PASS=
```

### 4. Ejecución
Iniciar los servicios de Apache y MySQL (ej: vía XAMPP).
Acceder desde el navegador a la carpeta del proyecto.
URL: http://localhost/Turnera/

El sistema redirigirá automáticamente a la vista principal mediante index.php.