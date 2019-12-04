<?php

namespace App\Modelos;

class Usuarios
{
    private $id;
    private $nombres;
    private $apellidos;
    private $tipo_documento;
    private $documento;
    private $telefono;
    private $direccion;
    private $user;
    private $password;
    private $rol;
    private $estado;

    /* Relaciones */
    private $VentasCliente;
    private $VentasEmpleado;

    /**
     * Usuarios constructor.
     * @param $id
     * @param $nombres
     * @param $apellidos
     * @param $tipo_documento
     * @param $documento
     * @param $telefono
     * @param $direccion
     * @param $user
     * @param $password
     * @param $rol
     * @param $estado
     */
    public function __construct($id, $nombres, $apellidos, $tipo_documento, $documento, $telefono, $direccion, $user, $password, $rol, $estado)
    {
        $this->id = $id;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->tipo_documento = $tipo_documento;
        $this->documento = $documento;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->user = $user;
        $this->password = $password;
        $this->rol = $rol;
        $this->estado = $estado;
    }


}