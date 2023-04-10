# Codechallenge #

Misión: Desarrollar una API para la gestión de una cesta de la compra.

## Requisitos ##

* Gestión de productos eficiente que permita: añadir, actualizar y eliminar productos del carrito.
* Obtener el número total de productos en el carrito.
* Confirmar la compra del carrito.
* Debe estar desacoplado del dominio.

## Instalación ##

Para la instalación del entorno de desarrollo y pruebas he preparado un contenedor docker. Para instalarlo:

* git clone https://github.com/javierdelavega/codechallenge-back.git
* docker-compose up -d

Tras la instalación tendremos dos servicios:

* **http://localhost:8005** la API.
* **http://localhost:8006** el frontend de demostración.

## Documentación y tests ##

La documentación está accesible a través del servicio frontend:

* **http://localhost:8006/appdoc/** la documentación de la App.
* **http://localhost:8006/apidoc/** la documentación y especificación de la api y sus endpoints.
* **http://localhost:8006/test_reports/** el coverage report de los tests.

Para la realización de los tests:

* docker exec -it codechallenge-back run-test-coverage

## Diseño ##

* He decidido utilizar el framework [Laravel](https://laravel.com) con el que estoy familiarizado. Versión 10.
* Para mantener la api desacoplada del dominio se utilizarán conexiones stateless, y estarán autenticadas con un Bearer Token.
* Para la gestión de los tokens de acceso y la autenticación se utlizará el paquete [Sanctum](https://laravel.com/docs/10.x/sanctum) de Laravel.
* Las requests a los endpoints de la api deberán estar autenticadas. Excepto el endpoint para obtener un nuevo token de acceso.
* Se obtendrá una lista de productos de la tienda desde una BD y el carrito solo permitirá añadir productos existentes en esa BD.
* El carrito estará asociado a un usuario para que pueda guardar los artículos en la cesta y posteriormente realizar la compra.

La aproximación que he seguido para los usuarios es la siguiente: 

* Si el usuario no presenta un token de acceso (primera visita) Debe solicitar uno nuevo a la api, creando para el un nuevo usuario Invitado. 
* Los usuarios invitados pueden registrarse, pasando a ser usuarios registrados, que se podrán identificar con unos credenciales en cualquier momento.
* Los usuarios invitados tienen una caducidad. Si no se registran se eliminarán de la BD pasado un tiempo (una semana para este ejemplo).
* La eliminación de usuarios se realizará con una tarea en el scheduler de Laravel.

## Desarrollo ##

* En primer lugar se ha definido la interfaz App\Contracts\CartServiceInterface que define las funcionalidades necesarias.
* La clase App\Services\CartService implementa las funcionalidades apoyada en el [ORM Eloquent](https://laravel.com/docs/10.x/eloquent) para el almacenamiento de datos.
* La clase App\Http\Controlers\CartController se encarga de gestionar las peticiones y respuestas a los endpoints de la API delegando en CartServiceInterface para la lógica de negocio.
* Se definen las Request necesarias para la validación de datos.
* Se programan las funcionalidades auxiliares para la gestion de usuarios y obtención de la lista de productos para la demostración
* Se desarrolla un pequeño frontend de demostración utilizando [vue](https://vuejs.org) + [axios](https://axios-http.com) + [vuetify](https://vuetifyjs.com/en/)
* Se escriben los tests de funcionalidades.
* Se realiza una documentación de la app. Y una documentación de la api con la especificación de los endpoints de la API.