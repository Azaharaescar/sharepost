# SharePost - Proyecto del curso de PHP MVC

Este es mi proyecto de la asignatura de Desarrollo Servidor, basado en el curso de Brad Traversy sobre PHP orientado a objetos y MVC.

## Qué es esto

Es una aplicación web tipo red social básica donde los usuarios pueden registrarse, hacer login, y publicar posts. Nada del otro mundo pero está bien para aprender el patrón MVC en PHP.

## Lo que he hecho

He seguido el curso de Brad Traversy "Object Oriented PHP and MVC" y he montado la aplicación SharePosts. Lo más importante del ejercicio era **arreglar un bug de seguridad** que permitía que cualquier usuario pudiera borrar o editar los posts de otros usuarios, lo cual es un problema bastante gordo.

### El bug que arreglé

El problema estaba en el archivo `app/controllers/Posts.php`. Resulta que en los métodos `edit()` y `delete()` no se verificaba correctamente si el usuario que intentaba editar o borrar un post era realmente el dueño.

**Lo que hice:**
- Añadí comprobaciones para verificar que el `user_id` del post coincide con el `user_id` de la sesión actual
- Si no coinciden, muestro un mensaje de error y redirijo al usuario
- Lo hice tanto en el método de editar como en el de borrar
- Puse comentarios explicando lo que hacía

Los comentarios están en las líneas 74-89, 131-143 y 173-182 del archivo Posts.php.

## Funcionalidades

La aplicación tiene:
- Sistema de registro e inicio de sesión
- Crear posts nuevos
- Ver todos los posts (de todos los usuarios)
- Editar tus propios posts (pero no los de otros)
- Borrar tus propios posts (pero no los de otros)
- Mensajes flash para cuando haces algo (tipo "Post creado" o "No puedes borrar esto")

## Tecnologías

- PHP con POO (Programación Orientada a Objetos)
- MySQL para la base de datos
- Bootstrap 4 para que se vea decente
- Patrón MVC (Model-View-Controller)
- PDO para las consultas de base de datos (más seguro que mysql_query)

## Estructura

El proyecto está organizado así:
- `app/controllers/` - Los controladores (Pages, Posts, Users)
- `app/models/` - Los modelos para la base de datos
- `app/views/` - Las vistas HTML
- `app/libraries/` - El core del framework (Core, Database, Controller)
- `public/` - Lo único accesible públicamente (index.php, CSS, JS)

## Nota importante

El archivo `app/config/config.php` NO está subido a GitHub porque tiene las contraseñas de la base de datos. Hay un archivo de ejemplo (`config.example.php`) que muestra qué datos hay que poner.

---

Proyecto del curso de Brad Traversy - Asignatura Desarrollo Servidor
