# GoHabits

## Guía de uso

Para poder utilizar la aplicación solo requiere la creación de una cuenta de usuario.
Desde ahí podrá utilizar la aplicación sin ningún problema.
Después de crear la cuenta puedes buscar a algún amigo pulsando en “buscar” o también puedes crear tus propios hábitos pulsando en “Agregar hábito”.
Una vez agregado puedes publicarlo para tus amigos si quieres.
Una vez está para realizar el hábito, debes dirigirte a la pestaña de hábitos y puedes comenzar a realizar el hábito.
Una vez lo comiences no subirás la experiencia hasta que completes todos los minutos que has marcado a la hora de crearlo.
En caso de dejarlo a la mitad se almacenará el tiempo restante para tu próxima sesión, para continuar desde donde lo habías dejado.

## Guía de instalación

Como es una aplicación web, si está desplegada solo será necesario acceder a través de la URL en cualquier navegador.
Si queremos trabajar localmente con el código deberíamos de clonar el proyecto desde el siguiente enlace:
https://github.com/LuisTona/TFGDaw

Para iniciar el servidor que aloja la base de datos, es necesario descargar e instalar XAMPP, que incluye tanto el servidor Apache como phpMyAdmin, herramienta que facilita la gestión de bases de datos MySQL.
Una vez instalado XAMPP, se procede a iniciar los servicios necesarios (Apache y MySQL) y acceder a phpMyAdmin.
Desde la interfaz de phpMyAdmin debemos importar el archivo `gohabits.sql` el cual se encuentra en el repositorio y en la documentación del proyecto.
El archivo contiene toda la estructura de la BBDD.
Debemos crear un usuario en MySQL y asignarle los permisos que necesitamos para poder conectarnos desde el backend.

En el archivo `conexion.php` se encuentra la información necesaria con la que se realiza la conexión a la BBDD (host, usuario, contraseña, base de datos), para realizar todas las funciones al completo de la BBDD.

Una vez realizados los pasos anteriores deberemos realizar la instalación de Composer y realizar la instalación de Firebase con el siguiente comando:
composer require firebase/php-jwt
