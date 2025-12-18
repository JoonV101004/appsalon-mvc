<?php

namespace Controllers;

use MVC\Router;
use Model\Cita;

class CitaController {

    public static function index(Router $router) {
        session_start();
        isAuth();

        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre'],
            'id' => $_SESSION['id']
        ]);
    }

    public static function misCitas(Router $router) {
        session_start();
        isAuth();

        $usuarioId = $_SESSION['id'];

        // Traer solo las citas del usuario
        $query = "SELECT * FROM citas WHERE usuarioid = ${usuarioId} AND estado != 'cancelada'";
        $citas = Cita::SQL($query);

        $router->render('cita/misCitas', [
            'citas' => $citas
        ]);
    }
    public static function cancelar() {
    session_start();
    isAuth();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;

        if ($id) {
            $cita = Cita::find($id);
            if ($cita) {
                $cita->estado = 'cancelada';
                $resultado = $cita->guardar();

                if ($resultado) {
                    header('Location: /misCitas?mensaje=Cita cancelada correctamente');
                    exit;
                } else {
                    header('Location: /misCitas?mensaje=Error al cancelar la cita');
                    exit;
                }
            }
        }
    }
}

}
