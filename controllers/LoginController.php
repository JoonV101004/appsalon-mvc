<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if(empty($alertas)) {
                // Comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);

                if($usuario) {
                    
                    // 1. VERIFICAR SI ESTÁ BLOQUEADO
                    // Si tiene 3 o más intentos, no lo dejamos pasar aunque la contraseña esté bien (opcional)
                    // O simplemente le avisamos.
                    if( intval($usuario->intentos) >= 3 ) {
                        Usuario::setAlerta('error', 'Tu cuenta ha sido bloqueada por intentos fallidos. Por favor recupera tu contraseña.');
                    } else {
                        
                        // 2. VERIFICAR EL PASSWORD
                        // Nota: Debemos cambiar un poco tu función comprobarPasswordAndVerificado
                        // para manejar esto desde el controlador, o modificarla allá.
                        // Para no romper tu código, hagámoslo así:

                        if( password_verify($auth->password, $usuario->password) ) {
                            // -- PASSWORD CORRECTO --

                            if($usuario->confirmado === "1") {
                                // El usuario entró bien, reiniciamos el contador a 0
                                $usuario->intentos = 0;
                                $usuario->guardar();

                                // ... (Tu código de sesión original) ...
                                session_start();
                                $_SESSION['id'] = $usuario->id;
                                $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                                $_SESSION['email'] = $usuario->email;
                                $_SESSION['login'] = true;

                                if($usuario->admin === "1") {
                                    $_SESSION['admin'] = $usuario->admin ?? null;
                                    header('Location: /admin');
                                } else {
                                    header('Location: /cita');
                                }
                            } else {
                                Usuario::setAlerta('error', 'Tu cuenta no ha sido confirmada');
                            }

                        } else {
                            // -- PASSWORD INCORRECTO --
                            
                            // Aumentamos los intentos
                            $usuario->intentos++;
                            $usuario->guardar();

                            if($usuario->intentos >= 3) {
                                Usuario::setAlerta('error', 'Cuenta Bloqueada. Has excedido el límite de intentos.');
                            } else {
                                Usuario::setAlerta('error', 'Password Incorrecto');
                            }
                        }
                    }

                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }

            }
        }

        $alertas = Usuario::getAlertas();
        
        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logout() {
        session_start();
        $_SESSION = [];
        session_destroy();
        header('Location: /');
    }

    public static function olvide(Router $router) {

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)) {
                 $usuario = Usuario::where('email', $auth->email);

                 if($usuario && $usuario->confirmado === "1") {
                        
                    // Generar un token
                    $usuario->crearToken();
                    $usuario->guardar();

                    //  Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // Alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu email');
                 } else {
                     Usuario::setAlerta('error', 'El Usuario no existe o no esta confirmado');
                     
                 }
            } 
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router) {
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        // Buscar usuario por su token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Válido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el nuevo password y guardarlo

            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)) {
                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();
                if($resultado) {
                    header('Location: /');
                }
            }

            if(empty($alertas)) {
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                
                // === AGREGAR ESTA LÍNEA ===
                $usuario->intentos = 0; // Desbloqueamos al usuario
                // ==========================

                $resultado = $usuario->guardar();
                if($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas, 
            'error' => $error
        ]);
    }

    public static function crear(Router $router) {
        $usuario = new Usuario;

        // Alertas vacias
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            // Revisar que alerta este vacio
            if(empty($alertas)) {
                // Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear el Password
                    $usuario->hashPassword();

                    // Generar un Token único
                    $usuario->crearToken();

                    // Enviar el Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    // Crear el usuario
                    $resultado = $usuario->guardar();
                    // debuguear($usuario);
                    if($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }
        
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router) {
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router) {
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token No Válido');
        } else {
            // Modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }
       
        // Obtener alertas
        $alertas = Usuario::getAlertas();

        // Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}