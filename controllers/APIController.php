<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController {
    public static function index() {
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar() {
        
        // Almacena la Cita y devuelve el ID
        $cita = new Cita($_POST);

        // --- VALIDACIÓN DE DISPONIBILIDAD ---
        // Consultamos si ya existe una cita con esa FECHA y HORA
        $query = "SELECT * FROM citas WHERE fecha = '" . $cita->fecha . "' AND hora = '" . $cita->hora . "' LIMIT 1";
        
        // Usamos el método SQL que tienes en ActiveRecord
        $existeCita = Cita::SQL($query);

        if($existeCita) {
            // Si ya existe, no guardamos y devolvemos error
            echo json_encode([
                'resultado' => false,
                'mensaje' => 'Lo sentimos, ese horario ya no está disponible'
            ]);
            return; // Detenemos la ejecución
        }

        $resultado = $cita->guardar();

        $id = $resultado['id'];

        // Almacena la Cita y el Servicio

        // Almacena los Servicios con el ID de la Cita
        $idServicios = explode(",", $_POST['servicios']);
        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        echo json_encode([
            'resultado' => true,
            'id' => $id,
            'mensaje' => 'Cita guardada correctamente'
        ]);
        
    }

    public static function eliminar() {
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }

    public static function cancelar() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        session_start();

        // ID de la cita enviada desde el formulario
        $id = $_POST['id'];

        // Buscar la cita
        $cita = Cita::find($id);

        // Validar que la cita exista
        if(!$cita) {
            echo json_encode([
                'resultado' => false,
                'mensaje' => 'La cita no existe'
            ]);
            return;
        }

        // Validar que la cita pertenece al usuario logueado
        if($cita->usuarioid !== $_SESSION['id']) {
            echo json_encode([
                'resultado' => false,
                'mensaje' => 'No tienes permiso para cancelar esta cita'
            ]);
            return;
        }

        // Marcar como cancelada (NO eliminarla)
        $cita->estado = 'cancelada';
        $cita->guardar();

        echo json_encode([
            'resultado' => true,
            'mensaje' => 'Cita cancelada correctamente'
            ]);
        }
    }

}