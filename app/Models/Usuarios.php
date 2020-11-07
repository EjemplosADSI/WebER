<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use JsonSerializable;

class Usuarios extends DBConnection implements Model, JsonSerializable
{
    /* Tipos de Datos => bool, int, float,  */
    private ?int $id;
    private string $nombres;
    private string $apellidos;
    private string $tipo_documento;
    private int $documento;
    private int $telefono;
    private string $direccion;
    private Carbon $fecha_nacimiento;
    private ?string $user;
    private ?string $password;
    private string $rol;
    private string $estado;
    private Carbon $fecha_registro;

    /* Relaciones */
    private array $VentasCliente;
    private array $VentasEmpleado;

    /**
     * Usuarios constructor. Recibe un array asociativo
     * @param array $usuario
     */
    public function __construct(array $usuario = [])
    {
        parent::__construct(); //Llama al contructor padre "la clase conexion" para conectarse a la BD
        $this->setId($usuario['id'] ?? NULL);
        $this->setNombres($usuario['nombres'] ?? '');
        $this->setApellidos($usuario['apellidos'] ?? '');
        $this->setTipoDocumento($usuario['tipo_documento'] ?? '');
        $this->setDocumento($usuario['documento'] ?? 0);
        $this->setTelefono($usuario['telefono'] ?? 0);
        $this->setDireccion($usuario['direccion'] ?? '');
        $this->setFechaNacimiento( !empty($usuario['fecha_nacimiento']) ? Carbon::parse($usuario['fecha_nacimiento']) : new Carbon());
        $this->setUser($usuario['user'] ?? null);
        $this->setPassword($usuario['password'] ?? null);
        $this->setRol($usuario['rol'] ?? '');
        $this->setEstado($usuario['estado'] ?? '');
        $this->setFechaRegistro(!empty($usuario['fecha_nacimiento']) ? Carbon::parse($usuario['fecha_registro']) : new Carbon());
    }

    function __destruct()
    {
        $this->Disconnect();
    }

    /**
     * @return int|mixed
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed|string
     */
    public function getNombres() : string
    {
        return $this->nombres;
    }

    /**
     * @param mixed|string $nombres
     */
    public function setNombres(string $nombres): void
    {

        $this->nombres = trim($nombres);
    }

    /**
     * @return mixed|string
     */
    public function getApellidos() : string
    {
        return $this->apellidos;
    }

    /**
     * @param mixed|string $apellidos
     */
    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    /**
     * @return mixed|string
     */
    public function getTipoDocumento() : string
    {
        return $this->tipo_documento;
    }

    /**
     * @param mixed|string $tipo_documento
     */
    public function setTipoDocumento(string $tipo_documento): void
    {
        $this->tipo_documento = $tipo_documento;
    }

    /**
     * @return int|mixed
     */
    public function getDocumento() : int
    {
        return $this->documento;
    }

    /**
     * @param int|mixed $documento
     */
    public function setDocumento(int $documento): void
    {
        $this->documento = $documento;
    }

    /**
     * @return int|mixed
     */
    public function getTelefono() : int
    {
        return $this->telefono;
    }

    /**
     * @param int|mixed $telefono
     */
    public function setTelefono(int $telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return mixed|string
     */
    public function getDireccion() : string
    {
        return $this->direccion;
    }

    /**
     * @param mixed|string $direccion
     */
    public function setDireccion(string $direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * @return Carbon|mixed
     */
    public function getFechaNacimiento() : Carbon
    {
        return $this->fecha_nacimiento->locale('es');
    }

    /**
     * @param Carbon|mixed $fecha_nacimiento
     */
    public function setFechaNacimiento(Carbon $fecha_nacimiento): void
    {
        $this->fecha_nacimiento = $fecha_nacimiento;
    }

    /**
     * @return mixed|string
     */
    public function getUser() : ?string
    {
        return $this->user;
    }

    /**
     * @param mixed|string $user
     */
    public function setUser(?string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed|string
     */
    public function getPassword() : ?string
    {
        return $this->password;
    }

    /**
     * @param mixed|string $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed|string
     */
    public function getRol() : string
    {
        return $this->rol;
    }

    /**
     * @param mixed|string $rol
     */
    public function setRol(string $rol): void
    {
        $this->rol = $rol;
    }

    /**
     * @return mixed|string
     */
    public function getEstado() : string
    {
        return $this->estado;
    }

    /**
     * @param mixed|string $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    /**
     * @return Carbon|mixed
     */
    public function getFechaRegistro() : Carbon
    {
        return $this->fecha_registro->locale('es');
    }

    /**
     * @param Carbon|mixed $fecha_registro
     */
    public function setFechaRegistro(Carbon $fecha_registro): void
    {
        $this->fecha_registro = $fecha_registro;
    }

    /**
     * @param string $query
     * @return bool|null
     */
    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':nombres' =>   $this->getNombres(),
            ':apellidos' =>   $this->getApellidos(),
            ':tipo_documento' =>  $this->getTipoDocumento(),
            ':documento' =>   $this->getDocumento(),
            ':telefono' =>   $this->getTelefono(),
            ':direccion' =>   $this->getDireccion(),
            ':fecha_nacimiento' =>  $this->getFechaNacimiento()->toDateString(), //YYYY-MM-DD
            ':user' =>  $this->getUser(),
            ':password' =>   $this->getPassword(),
            ':rol' =>   $this->getRol(),
            ':estado' =>   $this->getEstado(),
            ':fecha_registro' =>  $this->getFechaRegistro()->toDateTimeString() //YYYY-MM-DD HH:MM:SS
        ];
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    /**
     * @return bool|null
     */
    public function insert(): ?bool
    {
        $query = "INSERT INTO weber.usuarios VALUES (
            :id,:nombres,:apellidos,:tipo_documento,:documento,
            :telefono,:direccion,:fecha_nacimiento,:user,:password,
            :rol,:estado,:fecha_registro
        )";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    public function update(): ?bool
    {
        $query = "UPDATE weber.usuarios SET 
            nombres = :nombres, apellidos = :apellidos, tipo_documento = :tipo_documento, 
            documento = :documento, telefono = :telefono, direccion = :direccion, 
            fecha_nacimiento = :fecha_nacimiento, user = :user, password = :password, 
            rol = :rol, estado = :estado, fecha_registro = :fecha_registro WHERE id = :id";
        return $this->save($query);
    }

    /**
     * @param $query
     * @return Usuarios|array
     * @throws Exception
     */
    public static function search($query) : array
    {
        $arrUsuarios = array();
        $tmp = new Usuarios();
        $getrows = $tmp->getRows($query);
        foreach ($getrows as $valor) {
            $Usuario = new Usuarios($valor);
            array_push($arrUsuarios, $Usuario);
            $Usuario->Disconnect();
        }
        $tmp->Disconnect();
        return $arrUsuarios;
    }

    /**
     * @param $id
     * @return Usuarios
     * @throws Exception
     */
    public static function searchForId(int $id): Usuarios
    {
        if ($id > 0) {
            $getrow = (new Usuarios())->getRow("SELECT * FROM weber.usuarios WHERE id =?", array($id));
            $Usuario = new Usuarios($getrow);
            //$Usuario->Disconnect();
            return $Usuario;
        }else{
            throw new Exception('Id de usuario Invalido');
        }
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function deleted($id): bool
    {
        $User = Usuarios::searchForId($id); //Buscando un usuario por el ID
        $User->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $User->update();                    //Guarda los cambios..
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function getAll(): array
    {
        return Usuarios::search("SELECT * FROM weber.usuarios");
    }

    /**
     * @param $documento
     * @return bool
     * @throws Exception
     */
    public static function usuarioRegistrado($documento): bool
    {
        $result = Usuarios::search("SELECT * FROM weber.usuarios where documento = " . $documento);
        if ( count ($result) > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function nombresCompletos() : string
    {
        return $this->nombres . " " . $this->apellidos;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return "Nombres: $this->nombres, Apellidos: $this->nombres, Tipo Documento: $this->tipo_documento, Documento: $this->documento, Telefono: $this->telefono, Direccion: $this->direccion, Direccion: $this->fecha_nacimiento->toDateTimeString()";
    }

    public function Login($User, $Password){
        try {
            $resultUsuarios = Usuarios::search("SELECT * FROM usuarios WHERE user = '$User'");
            if(count($resultUsuarios) >= 1){
                if($resultUsuarios[0]->password == $Password){
                    if($resultUsuarios[0]->estado == 'Activo'){
                        return $resultUsuarios[0];
                    }else{
                        return "Usuario Inactivo";
                    }
                }else{
                    return "Contraseña Incorrecta";
                }
            }else{
                return "Usuario Incorrecto";
            }
        } catch (Exception $e) {
            GeneralFunctions::console($e,'error','errorStack');
            return "Error en Servidor";
        }
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'nombres' => $this->getNombres(),
            'apellidos' => $this->getApellidos(),
            'tipo_documento' => $this->getTipoDocumento(),
            'documento' => $this->getDocumento(),
            'telefono' => $this->getTelefono(),
            'direccion' => $this->getDireccion(),
            'fecha_nacimiento' => $this->getFechaNacimiento()->toDateString(),
            'user' => $this->getUser(),
            'password' => $this->getPassword(),
            'rol' => $this->getRol(),
            'estado' => $this->getEstado(),
            'fecha_registro' => $this->getFechaRegistro()->toDateTimeString(),
        ];
    }
}