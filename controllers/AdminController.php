<?php 

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController {
    public static function index( Router $router ) {
        session_start();

        // Validación de rol
        isAdmin();

        // Fecha seleccionada o la actual
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $fechas = explode('-', $fecha);

        // Validar formato de fecha
        if( !checkdate( $fechas[1], $fechas[2], $fechas[0]) ) {
            header('Location: /404');
            exit;
        }

        // Consulta SQL: ajustada para DATE o DATETIME
        $consulta = "SELECT citas.id, citas.hora, CONCAT(usuarios.nombre, ' ', usuarios.apellido) AS cliente, 
                            usuarios.email, usuarios.telefono, servicios.nombre AS servicio, servicios.precio  
                     FROM citas  
                     LEFT OUTER JOIN usuarios 
                        ON citas.usuarioId = usuarios.id  
                     LEFT OUTER JOIN citasServicios 
                        ON citasServicios.citaId = citas.id 
                     LEFT OUTER JOIN servicios 
                        ON servicios.id = citasServicios.servicioId 
                     WHERE DATE(citas.fecha) = '${fecha}'";

        // Ejecutar consulta
        $citas = AdminCita::SQL($consulta);

        // 🔍 Depuración opcional: activa si quieres ver qué devuelve
        /*
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        echo "<pre>";
        echo "Consulta ejecutada:\n" . $consulta . "\n\n";
        var_dump($citas);
        echo "</pre>";
        exit;
        */

        // Renderizar vista
        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas'  => $citas, 
            'fecha'  => $fecha
        ]);
    }
}
