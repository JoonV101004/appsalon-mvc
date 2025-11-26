<?php 

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController {
    public static function index(Router $router) {
        session_start();

        // Validación de rol
        isAdmin();

        // Fecha seleccionada o la actual
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $fechas = explode('-', $fecha);

        // Validar formato de fecha
        if (!checkdate($fechas[1], $fechas[2], $fechas[0])) {
            header('Location: /404');
            exit;
        }

        // Consulta SQL con las tablas correctas
        $consulta = "SELECT citas.id, citas.hora, CONCAT(usuarios.nombre, ' ', usuarios.apellido) AS cliente,
                            usuarios.email, usuarios.telefono,
                            servicios.nombre AS servicio, servicios.precio
                     FROM citas
                     LEFT JOIN usuarios 
                        ON citas.usuarioid = usuarios.id
                     LEFT JOIN citasservicios 
                        ON citasservicios.citaId = citas.id
                     LEFT JOIN servicios 
                        ON servicios.id = citasservicios.servicioId
                     WHERE DATE(citas.fecha) = '${fecha}'";

        // Ejecutar consulta
        $citas = AdminCita::SQL($consulta);

        // Renderizar vista
        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas'  => $citas,
            'fecha'  => $fecha
        ]);
    }
}

