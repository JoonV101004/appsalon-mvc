<?php

namespace Model;

class CitaServicio extends ActiveRecord {
    protected static $tabla = 'citasservicios';
    protected static $columnasDB = ['id', 'citaid', 'servicioid'];

    public $id;
    public $citaId;
    public $servicioId;

    public function __construct($args = [])
    {
       $this->id = $args['id'] ?? null;
       $this->citaId = $args['citaid'] ?? '';
       $this->servicioId = $args['servicioid'] ?? ''; 
    }
}