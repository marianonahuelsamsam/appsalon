<?php

namespace Controller;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

class LoginController {

    public static function login(Router $router) {
        // Ruta POST
        /*Se inicia la variable $auth como una instancia de la clase Usuario, pero no le pasamos nada al constructor
        para que se llene al momento de enviar el formulario y poder mantener el mail en el input en caso de no validar*/
        $auth = new Usuario;
        // Iniciamos vacio el arreglo de alertas que se irá llenando de acuerdo a los datos recibidos-
        $alertas = [];
        //Rutas POST "/olvide"
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            // Instanciamos la clase usuario con los datos recibidos del POST.
            $auth = new Usuario($_POST);
            // Validamos que todos los campos hayan sido introducidos y llenamos el arreglo $alertas en caso de que no.
            $alertas = $auth->validarUsuario();
            // En caso de que $alertas no tenga datos, la validación ha sido exitosa, se han llenado todos los campos
            if (empty($alertas)) {
                /*     En $usuario almacenaremos los datos del usuario que coincidan con el mail recibido desde el cliente.
                La función "where" recibe:
                Primer parámetro: La columna en la que se debe buscar.
                Segundo parámetro: Lo que hay que buscar.     */ 
                $usuario = Usuario::where("email", $auth->email);

                // De acuerdo a si fue encontrado, o no, un usuario que coincida con el mail recibido:
                if ($usuario) {
                    // Si está verificado y es correcto el password.
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        // Autenticar al usuario.
                        if(!isset($_SESSION)) {
                            session_start();
                        }

                        $_SESSION["id"] = $usuario->id;
                        $_SESSION["nombre"] = $usuario->nombre;
                        $_SESSION["email"] = $usuario->email;
                        $_SESSION["login"] = true;

                        // Redireccionamiento
                        if($usuario->admin === "1") {
                            $_SESSION["admin"] = $usuario->admin ?? null;
                            header("location: /admin");
                        } else {
                            header("location: /cita");
                        }

                    };
                } else {
                    Usuario::setAlerta("error", "Usuario no encontrado");
                }
            }

            $alertas = Usuario::getAlertas();
        };

        $router->render("auth/login", [
            "alertas" => $alertas,
            "email" => $auth->email
        ]);        
    }

    public static function logout() {
        // debuguear($_SESSION);

        $_SESSION = [];
        header("location: /");
    }
     
    public static function olvide(Router $router) {
        // Iniciamos el arreglo de alertas vacío, se irá llenando de acuerdo a las validaciones.
        $alertas = [];
        // Iniciamos la instancia de usuario por fuera de POST para mantener el email que recibimos en su campo.
        $auth = new Usuario;
        // Ruta POST "/olvide"
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            // Le pasamos a la clase de usuario el email recibido por POST.
            $auth = new Usuario($_POST);
            // Validamos que el email haya sido ingresado por el cliente.
            $alertas = $auth->validarEmail();
            // Si $alertas está vacío la validación ha sido exitosa
            if (empty($alertas)) {
                // Buscamos al usuario en la base de datos guiandonos por su email
                $usuario = Usuario::where("email", $auth->email);

                if ($usuario && $usuario->confirmado === "1") {
                    $usuario->crearToken();
                    $usuario->guardar();
                    $alertas = Usuario::setAlerta("exito", "Revisa tu email");

                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarInstruccionesDeRecuperacion();
                    
                } else {
                    $alertas = Usuario::setAlerta("error", "El usuario no existe o no ha sido confirmado.");
                }
                
            } 
        };

        $alertas = Usuario::getAlertas();

        $router->render("auth/olvide-password", [
            "alertas" => $alertas,
            "auth" => $auth

        ]);
    }

    public static function recuperar(Router $router) {
        $alertas = []; 
        // Variable helper para ocultar el formulario en caso de que el token no sea válido.
        $error = false;
        // Obtenemos el token del usuario desde get.
        $token = s($_GET["token"] ?? "");
        // Si token no obtiene un valor desde GET detenemos la renderización de la vista.
        if(!$token) {
            Usuario::setAlerta("error", "Token no valido");
            $error = true;
        }

        // Encontrar al usuario por su token
        $usuario = Usuario::where("token", $token ?? "");
        // Si no fue posible encontrar un usuario a través de su token:
        if(empty($usuario)) {
            Usuario::setAlerta("error", "Token no valido");
            // Detenemos el código en la vista que nos muestra el formulario
            $error = true;
        } 

        // Ruta POST, al momento de clickear el botón de enviar contraseña:
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            // Leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            // Validamos que el password exista y tenga una extensión mayor a 6 caracteres
            $alertas = $password->validarPassword();
            
            // Si el arreglo $alertas está vacío es porque se recibió la contraseña
            if(empty($alertas)) {
                // Eliminamos el password actual del usuario
                $usuario->password = null;
                // Le asignamos al usuario el password recibido por el cliente en la instancia "$password".
                $usuario->password = $password->password;
                // Hasheamos el nuevo password
                $usuario->hashPassword();
                // Eliminamos el nuevo token
                $usuario->token = null;

                /* Guardamos la nueva contraseña del usuario en la base de datos y asignamos 
                a $resultado la operación para redireccionar.*/
                $resultado = $usuario->guardar();
                if($resultado) {
                    header("location: /");
                }
            }
            
        }

        $alertas = Usuario::getAlertas();

        $router->render("auth/recuperar-password", [
            "alertas" => $alertas,
            "error" => $error
        ]);
    }

    // Sección Crear Cuenta
    public static function crear(Router $router) {
        // Instanciamos el objeto, sin pasarle argumentos para tenerlo en memoria (importante para la retención de datos - sincronizar)
        $usuario = new Usuario();
        // Inicializamos el arreglo de alertas como vacío.
        $alertas = [];

        // Ruta post de /crear:

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            // F12 para más detalles de éste método.
            $usuario->sincronizar($_POST);
            // Validación.
            $alertas = $usuario->validarNuevaCuenta();
            
            // Si alertas es un arreglo vacío (se llenaron todos los campos).
            if(empty($alertas)) {
                // Verificar que el usuario no esté registrado.
                $resultado = $usuario->existeUsuario();

                // Si el usuario está registrado obtendremos las alertas desde la clase (getAlertas).
                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    // Registrar Usuario
                    // Hashear Password 
                    $usuario->hashPassword();
                    
                    // Generar token unico
                    $usuario->crearToken();

                    // Enviar el email con el token
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion(); 

                    /*Si los datos del usuario fueron almacenados coorrectamente, lo enviaremos hacia la URL en la cual le 
                    comunicaremos que enviamos las instrucciones para que confirme su cuenta a su dirección de email.*/
                    $resultado = $usuario->guardar();
                    if($resultado) {
                        header("location: /mensajeConfirmacion");
                    }
                    
                }
            }
        };

        $router->render("auth/crear-cuenta", [
            "usuario" => $usuario,
            "alertas" =>$alertas

        ]);
    }

    public static function mensajeConfirmacion (Router $router) {

        $router->render("auth/mensaje");

    }

    public static function confirmar(Router $router) {
        
        $alertas = [];
        $token = s($_GET["token"]);

        $usuario = Usuario::where("token", $token);

        if(empty($usuario)) {
            Usuario::setAlerta("error", "Token no válido");
        } else {

            $usuario->confirmado = 1;
            $usuario->token = "";

            $usuario->guardar();

            Usuario::setAlerta("exito", "Cuenta confirmada correctamente");
        }

        // debuguear($usuario);

        // Obtener alertas.
        $alertas = Usuario::getAlertas();

        $router->render("auth/confirmar-cuenta", [
            "alertas" => $alertas
        ]);

    }
}