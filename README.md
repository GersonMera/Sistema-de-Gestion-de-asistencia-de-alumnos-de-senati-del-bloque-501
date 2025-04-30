
# Sistema de Gestión de Asistencia de Alumnos

## Descripción del Proyecto

Este sistema permite la gestión de asistencia de estudiantes a través de un innovador código QR único. Los estudiantes podrán registrar su asistencia de forma rápida y eficiente utilizando sus dispositivos móviles.

## Funcionalidades

### 🔹 Registro y Login
- Los estudiantes pueden registrarse utilizando su ID institucional, nombre, apellido y una contraseña.
- Ingreso al sistema mediante la autenticación de ID y contraseña.

### 🔹 Dashboard del Estudiante
- Acceso al historial de asistencias.
- Visualización y actualización de datos personales.
- Uso de la cámara del dispositivo móvil para escanear el código QR y registrar la asistencia.

### 🔹 Gestión de Asistencia
- Los administradores pueden ver el historial de asistencia de los estudiantes.
- El sistema permite gestionar y consultar la asistencia de cada estudiante de forma sencilla.

## Tecnologías Utilizadas

Este sistema ha sido desarrollado utilizando las siguientes tecnologías:
- **PHP**: Backend para la gestión de procesos y lógica de negocio.
- **MySQL**: Base de datos para almacenar los registros de los estudiantes y asistencia.
- **HTML/CSS**: Frontend para la creación de las interfaces de usuario.
- **JavaScript**: Para la interactividad en el cliente, como la toma de fotos para el QR y la validación en el navegador.

## Instrucciones de Instalación

1. **Clonar el repositorio**:
   ```bash
   git clone https://github.com/usuario/https://github.com/GersonMera/Sistema-de-Gestion-de-asistencia-de-alumnos-de-senati-del-bloque-501.git
   ```

2. **Configurar la base de datos**:
   - Importa el archivo `attendance_system (1).sql` en tu servidor MySQL.
   - Asegúrate de configurar las credenciales de la base de datos en el archivo `config/database.php`.

3. **Configurar el servidor**:
   - Asegúrate de tener un servidor web que soporte PHP (como Apache o Nginx).
   - Sube los archivos del proyecto al servidor.

4. **Acceder al sistema**:
   - Accede a la URL de tu servidor (ej. `http://localhost/index.php`) para usar el sistema.

## Contribuciones

Si deseas contribuir al proyecto, sigue estos pasos:

1. Forkea el repositorio.
2. Crea una nueva rama (`git checkout -b feature/nueva-caracteristica`).
3. Haz commit de tus cambios (`git commit -am 'Agrega nueva característica'`).
4. Haz push a la rama (`git push origin feature/nueva-caracteristica`).
5. Abre un Pull Request.

## Licencia

Este proyecto está bajo la Licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más detalles.
