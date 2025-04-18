Desarrollar un sistema de registro de asistencia mediante un código QR único. A continuación, les explico brevemente cómo funcionará:

🔹 Registro y Login:
El usuario (estudiante) se registrará usando su ID institucional, nombre, apellido y una contraseña. Luego podrá iniciar sesión con su ID y contraseña.

🔹 Dashboard del Estudiante:
Una vez dentro, tendrá acceso a un dashboard donde podrá:

Ver su historial de asistencias.

Acceder a sus datos personales.

Usar la cámara de su celular para escanear un código QR que marcará su asistencia tanto al ingresar como al salir.

🔹 Panel del Administrador:
El administrador tendrá funciones como:

Generar el código QR.

Ver la lista de estudiantes.

Consultar detalles individuales.

Generar reportes de asistencia (presentes y ausentes).

Ver reportes por estudiante.

Actualmente ya tenemos el backend desarrollado. Lo que falta es aplicar los estilos con CSS propio, pero es importante mantener una línea visual coherente. La idea es que no usemos colores distintos entre sí (por ejemplo, azul con blanco por un lado y verde con rojo por otro), para que todo tenga una apariencia uniforme.

Cada uno podrá trabajar en su parte de CSS, pero siguiendo un mismo estilo base.

👉 Aquí les dejo un resumen de las vistas implementadas hasta el momento:

Administrador:

Dashboard

Generar QR

Reporte general

Detalle del estudiante

Lista de estudiantes

Reporte por estudiante

Estudiantes (General):

Historial de asistencia

Dashboard

Login

Registro


accesos: 
ADMIN001
admin123
