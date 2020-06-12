<?php

namespace App\Models;

require('BasicModel.php');

class Usuarios extends BasicModel
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
    public function __construct($usuario = array())
    {
        parent::__construct(); //Llama al contructor padre "la clase conexion" para conectarme a la BD
        $this->id = $usuario['id'] ?? null;
        $this->nombres = $usuario['nombres'] ?? null;
        $this->apellidos = $usuario['apellidos'] ?? null;
        $this->tipo_documento = $usuario['tipo_documento'] ?? null;
        $this->documento = $usuario['documento'] ?? null;
        $this->telefono = $usuario['telefono'] ?? null;
        $this->direccion = $usuario['direccion'] ?? null;
        $this->user = $usuario['user'] ?? null;
        $this->password = $usuario['password'] ?? null;
        $this->rol = $usuario['rol'] ?? null;
        $this->estado = $usuario['estado'] ?? null;
    }

    /* Metodo destructor cierra la conexion. */
    function __destruct() {
        $this->Disconnect();
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNombres(): string
    {
        return $this->nombres;
    }

    /**
     * @param string $nombres
     */
    public function setNombres(string $nombres): void
    {
        $this->nombres = $nombres;
    }

    /**
     * @return string
     */
    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    /**
     * @param string $apellidos
     */
    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    /**
     * @return string
     */
    public function getTipoDocumento(): string
    {
        return $this->tipo_documento;
    }

    /**
     * @param string $tipo_documento
     */
    public function setTipoDocumento(string $tipo_documento): void
    {
        $this->tipo_documento = $tipo_documento;
    }

    /**
     * @return int
     */
    public function getDocumento(): int
    {
        return $this->documento;
    }

    /**
     * @param int $documento
     */
    public function setDocumento(int $documento): void
    {
        $this->documento = $documento;
    }

    /**
     * @return int
     */
    public function getTelefono(): int
    {
        return $this->telefono;
    }

    /**
     * @param int $telefono
     */
    public function setTelefono(int $telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return string
     */
    public function getDireccion(): string
    {
        return $this->direccion;
    }

    /**
     * @param string $direccion
     */
    public function setDireccion(string $direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getRol(): string
    {
        return $this->rol;
    }

    /**
     * @param string $rol
     */
    public function setRol(string $rol): void
    {
        $this->rol = $rol;
    }

    /**
     * @return string
     */
    public function getEstado(): string
    {
        return $this->estado;
    }

    /**
     * @param string $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    /**
     * @return mixed
     */
    public function getVentasCliente()
    {
        return $this->VentasCliente;
    }

    /**
     * @param mixed $VentasCliente
     */
    public function setVentasCliente($VentasCliente): void
    {
        $this->VentasCliente = $VentasCliente;
    }

    /**
     * @return mixed
     */
    public function getVentasEmpleado()
    {
        return $this->VentasEmpleado;
    }

    /**
     * @param mixed $VentasEmpleado
     */
    public function setVentasEmpleado($VentasEmpleado): void
    {
        $this->VentasEmpleado = $VentasEmpleado;
    }

    public function create() : bool
    {
        $result = $this->insertRow("INSERT INTO weber.usuarios VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array(
            $this->nombres,
            $this->apellidos,
            $this->tipo_documento,
            $this->documento,
            $this->telefono,
            $this->direccion,
            $this->user,
            $this->password,
            $this->rol,
            $this->estado
            )
        );
        $this->Disconnect();
        return $result;
    }

    public function update() : bool
    {
        $result = $this->updateRow("UPDATE weber.usuarios SET nombres = ?, apellidos = ?, tipo_documento = ?, documento = ?, telefono = ?, direccion = ?, user = ?, password = ?, rol = ?, estado = ? WHERE id = ?", array(
                $this->nombres,
                $this->apellidos,
                $this->tipo_documento,
                $this->documento,
                $this->telefono,
                $this->direccion,
                $this->user,
                $this->password,
                $this->rol,
                $this->estado,
                $this->id
            )
        );
        $this->Disconnect();
        return $result;
    }

    public function deleted($id) : bool
    {
        $User = Usuarios::searchForId($id); //Buscando un usuario por el ID
        $User->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $User->update();                    //Guarda los cambios..
    }

    public static function search($query) : array
    {
        $arrUsuarios = array();
        $tmp = new Usuarios();
        $getrows = $tmp->getRows($query);

        foreach ($getrows as $valor) {
            $Usuario = new Usuarios();
            $Usuario->id = $valor['id'];
            $Usuario->nombres = $valor['nombres'];
            $Usuario->apellidos = $valor['apellidos'];
            $Usuario->tipo_documento = $valor['tipo_documento'];
            $Usuario->documento = $valor['documento'];
            $Usuario->telefono = $valor['telefono'];
            $Usuario->direccion = $valor['direccion'];
            $Usuario->user = $valor['user'];
            $Usuario->password = $valor['password'];
            $Usuario->rol = $valor['rol'];
            $Usuario->estado = $valor['estado'];
            $Usuario->Disconnect();
            array_push($arrUsuarios, $Usuario);
        }
        $tmp->Disconnect();
        return $arrUsuarios;
    }

    public static function searchForId($id) : Usuarios
    {
        $Usuario = null;
        if ($id > 0){
            $Usuario = new Usuarios();
            $getrow = $Usuario->getRow("SELECT * FROM weber.usuarios WHERE id =?", array($id));
            $Usuario->id = $getrow['id'];
            $Usuario->nombres = $getrow['nombres'];
            $Usuario->apellidos = $getrow['apellidos'];
            $Usuario->tipo_documento = $getrow['tipo_documento'];
            $Usuario->documento = $getrow['documento'];
            $Usuario->telefono = $getrow['telefono'];
            $Usuario->direccion = $getrow['direccion'];
            $Usuario->user = $getrow['user'];
            $Usuario->password = $getrow['password'];
            $Usuario->rol = $getrow['rol'];
            $Usuario->estado = $getrow['estado'];
        }
        $Usuario->Disconnect();
        return $Usuario;
    }

    public static function getAll() : array
    {
        return Usuarios::search("SELECT * FROM weber.usuarios");
    }

    public static function usuarioRegistrado ($documento) : bool
    {
        $result = Usuarios::search("SELECT id FROM weber.usuarios where documento = ".$documento);
        if (count($result) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function __toString()
    {
        //
        return $this->nombres." ".$this->apellidos;
    }

}