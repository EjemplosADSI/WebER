<?php

namespace App\Modelos;

class Usuarios extends db_abstract_class
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
        $this->id = $usuario['id'];
        $this->nombres = $usuario['nombres'];
        $this->apellidos = $usuario['apellidos'];
        $this->tipo_documento = $usuario['tipo_documento'];
        $this->documento = $usuario['documento'];
        $this->telefono = $usuario['telefono'];
        $this->direccion = $usuario['direccion'];
        $this->user = $usuario['user'];
        $this->password = $usuario['password'];
        $this->rol = $usuario['rol'];
        $this->estado = $usuario['estado'];
    }

    /* Metodo destructor cierra la conexion. */
    function __destruct() {
        $this->Disconnect();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * @param mixed $nombres
     */
    public function setNombres($nombres): void
    {
        $this->nombres = $nombres;
    }

    /**
     * @return mixed
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * @param mixed $apellidos
     */
    public function setApellidos($apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    /**
     * @return mixed
     */
    public function getTipoDocumento()
    {
        return $this->tipo_documento;
    }

    /**
     * @param mixed $tipo_documento
     */
    public function setTipoDocumento($tipo_documento): void
    {
        $this->tipo_documento = $tipo_documento;
    }

    /**
     * @return mixed
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * @param mixed $documento
     */
    public function setDocumento($documento): void
    {
        $this->documento = $documento;
    }

    /**
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * @param mixed $rol
     */
    public function setRol($rol): void
    {
        $this->rol = $rol;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado
     */
    public function setEstado($estado): void
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

    protected function insertar()
    {
        $this->insertRow("INSERT INTO weber.usuarios VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array(
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
            )
        );
        $this->Disconnect();
    }

    protected function editar()
    {
        $this->updateRow("UPDATE weber.usuarios SET nombres = ?, apellidos = ?, tipo_documento = ?, documento = ?, telefono = ?, direccion = ?, user = ?, password = ?, rol = ?, estado = ? WHERE id = ?", array(
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
    }

    protected function eliminar($id)
    {
        // TODO: Implement eliminar() method.
    }

    protected static function buscar($query)
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

    protected static function buscarForId($id)
    {
        $Usuario = new Usuarios();
        if ($id > 0){
            $getrow = $Usuario->getRow("SELECT * FROM weber.usuarios WHERE id =?", array($id));
            $Usuario->id = $getrow['idPersona'];
            $Usuario->nombres = $getrow['Tipo_Documento'];
            $Usuario->apellidos = $getrow['Documento'];
            $Usuario->tipo_documento = $getrow['Nombres'];
            $Usuario->documento = $getrow['Apellidos'];
            $Usuario->telefono = $getrow['Telefono'];
            $Usuario->direccion = $getrow['Direccion'];
            $Usuario->user = $getrow['Correo'];
            $Usuario->password = $getrow['Foto'];
            $Usuario->rol = $getrow['NRP'];
            $Usuario->estado = $getrow['Fecha_Registro'];
            $Usuario->Disconnect();
            return $Usuario;
        }else{
            $Usuario->Disconnect();
            unset($Usuario);
            return NULL;
        }
    }

    protected static function getAll()
    {
        return Usuarios::buscar("SELECT * FROM weber.usuarios");
    }

}