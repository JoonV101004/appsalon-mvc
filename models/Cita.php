<?php

namespace Model;

class Cita extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'citas';
    protected static $columnasDB = ['id', 'fecha', 'hora', 'usuarioid', 'estado'];

    public $id;
    public $fecha;
    public $hora;
    public $usuarioid;
    public $estado; // ✅ propiedad agregada

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->usuarioid = $args['usuarioid'] ?? '';
        $this->estado = $args['estado'] ?? 'activa'; // ✅ valor por defecto
    }
}
