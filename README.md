# Fiter - Sistema de Gestión de Usuarios

## Descripción

Fiter es un sistema de gestión de usuarios desarrollado con PHP y MySQL. Permite registrar usuarios, iniciar sesión y administrar la información desde un panel de administración.

## Tecnologías utilizadas

- HTML
- CSS
- PHP
- MySQL
- PDO
- XAMPP
- phpMyAdmin

## Funciones principales

- Registro de usuarios.
- Inicio y cierre de sesión.
- Panel personal del usuario.
- Panel de administración.
- Búsqueda de usuarios.
- Edición de usuarios.
- Archivado de usuarios.
- Reactivación de usuarios.
- Control de roles entre usuarios y administradores.
- Validación de datos.
- Contraseñas almacenadas mediante hash.

## Base de datos

La base de datos utilizada se llama `fiter`.

La tabla principal es `usuarios`.

El archivo `fiter.sql` contiene la estructura y los datos necesarios para importar la base de datos.

## Instalación

1. Instalar XAMPP.
2. Iniciar Apache y MySQL.
3. Copiar la carpeta del proyecto dentro de `htdocs`.
4. Crear o importar la base de datos `fiter` desde phpMyAdmin.
5. Importar el archivo `fiter.sql`.
6. Abrir el proyecto desde:

`http://localhost/Santiago_Is_Proyecto_Final/`

## Seguridad

El sistema utiliza `password_hash()` para proteger las contraseñas y `password_verify()` para comprobarlas durante el inicio de sesión.

También utiliza consultas preparadas mediante PDO para evitar problemas de inyección SQL y sesiones para controlar el acceso de los usuarios.

## Autor

Santiago Is