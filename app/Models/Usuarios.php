<?php
namespace App\Models;

use App\Interfaces\Model;
use Carbon\Carbon;
use Exception;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;

require_once ("AbstractDBConnection.php");
require_once (__DIR__."\..\Interfaces\Model.php");
require_once (__DIR__.'/../../vendor/autoload.php');

class Usuarios extends AbstractDBConnection implements Model
{

    /* Tipos de Datos => bool, int, float,  */
    private ?int $id;
    private string $nombres;
    private string $apellidos;
    private string $tipo_documento;
    private int $documento;
    private int $telefono;
    private string $direccion;
    private int $municipio_id;
    private Carbon $fecha_nacimiento;
    private ?string $user;
    private ?string $password;
    private ?string $foto;
    private string $rol;
    private string $estado;
    private Carbon $created_at;
    private Carbon $updated_at;

    /* Seguridad de Contraseña */
    const HASH = PASSWORD_DEFAULT;
    const COST = 10;

    /**
     * Usuarios constructor. Recibe un array asociativo
     * @param array $usuario
     */
    public function __construct(array $usuario = [])
    {
        parent::__construct();
        $this->setId($usuario['id'] ?? null);
        $this->setNombres($usuario['nombres'] ?? '');
        $this->setApellidos($usuario['apellidos'] ?? '');
        $this->setTipoDocumento($usuario['tipo_documento'] ?? '');
        $this->setDocumento($usuario['documento'] ?? 0);
        $this->setTelefono($usuario['telefono'] ?? 0);
        $this->setDireccion($usuario['direccion'] ?? '');
        $this->setMunicipioId($usuario['municipio_id'] ?? 0);
        $this->setFechaNacimiento(!empty($usuario['fecha_nacimiento']) ?
            Carbon::parse($usuario['fecha_nacimiento']) : new Carbon());
        $this->setUser($usuario['user'] ?? null);
        $this->setPassword($usuario['password'] ?? null);
        $this->setFoto($usuario['foto'] ?? null);
        $this->setRol($usuario['rol'] ?? '');
        $this->setEstado($usuario['estado'] ?? '');
        $this->setCreatedAt(!empty($usuario['created_at']) ?
            Carbon::parse($usuario['created_at']) : new Carbon());
        $this->setUpdatedAt(!empty($usuario['updated_at']) ?
            Carbon::parse($usuario['updated_at']) : new Carbon());
    }

    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->Disconnect();
        }
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
    #[Pure]
    public function getNombres() : null|string
    {
        return ucwords($this->nombres);
    }

    /**
     * @param mixed|string $nombres
     */
    public function setNombres(string $nombres): void
    {
        $this->nombres = trim(mb_strtolower($nombres, 'UTF-8'));
    }

    /**
     * @return mixed|string
     */
    #[Pure]
    public function getApellidos() : ?string
    {
        return ucwords($this->apellidos);
    }

    /**
     * @param mixed|string $apellidos
     */
    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = trim(mb_strtolower($apellidos, 'UTF-8'));
    }

    /**
     * @return mixed|string
     */
    public function getTipoDocumento() : ?string
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
    public function getDocumento() : ?int
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
    public function getTelefono() : ?int
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
    public function getDireccion() : ?string
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
     * @return int
     */
    public function getMunicipioId(): int
    {
        return $this->municipio_id;
    }

    /**
     * @param int $municipio_id
     */
    public function setMunicipioId(int $municipio_id): void
    {
        $this->municipio_id = $municipio_id;
    }

    /**
     * @return Carbon|mixed
     */
    public function getFechaNacimiento() : ?Carbon
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
     * @return string|null
     */
    public function getFoto(): ?string
    {
        return $this->foto;
    }

    /**
     * @param string|null $foto
     */
    public function setFoto(?string $foto): void
    {
        $this->foto = $foto;
    }

    /**
     * @return mixed|string
     */
    public function getRol() : ?string
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
    public function getEstado() : ?string
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
    public function getCreatedAt() : ?Carbon
    {
        return $this->created_at->locale('es');
    }

    /**
     * @param Carbon|mixed $created_at
     */
    public function setCreatedAt(Carbon $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at->locale('es');
    }

    /**
     * @param Carbon $updated_at
     */
    public function setUpdatedAt(Carbon $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /* Relaciones */

    /**
     * @return Municipios|null
     */
    public function getMunicipio(): Municipios|null
    {
        if (!empty($this->municipio_id)) {
            return Municipios::searchForId($this->municipio_id) ?? new Municipios();
        }
        return null;
    }

    /**
     * @return array|null
     */
    public function getVentasCliente(): ?array
    {
        if (!empty($this->getId())) {
            return Ventas::search('SELECT * FROM ventas WHERE cliente_id = '.$this->getId());
        }
        return null;
    }

    /**
     * @return array|null
     */
    public function getVentasEmpleado(): ?array
    {
        if (!empty($this->getId())) {
            return Ventas::search('SELECT * FROM ventas WHERE empleado_id = '.$this->getId());
        }
        return null;
    }

    /**
     * @param string $query
     * @return bool|null
     */
    protected function save(string $query): ?bool
    {
        $hashPassword = password_hash($this->password, self::HASH, ['cost' => self::COST]);

        $arrData = [
            ':id' =>    $this->getId(),
            ':nombres' =>   $this->getNombres(),
            ':apellidos' =>   $this->getApellidos(),
            ':tipo_documento' =>  $this->getTipoDocumento(),
            ':documento' =>   $this->getDocumento(),
            ':telefono' =>   $this->getTelefono(),
            ':direccion' =>   $this->getDireccion(),
            ':municipio_id' =>   $this->getMunicipioId(),
            ':fecha_nacimiento' =>  $this->getFechaNacimiento()->toDateString(), //YYYY-MM-DD
            ':user' =>  $this->getUser(),
            ':password' =>   $hashPassword,
            ':foto' =>   $this->getFoto(),
            ':rol' =>   $this->getRol(),
            ':estado' =>   $this->getEstado(),
            ':created_at' =>  $this->getCreatedAt()->toDateTimeString(), //YYYY-MM-DD HH:MM:SS
            ':updated_at' =>  $this->getUpdatedAt()->toDateTimeString()
        ];
        $this->Connect();
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
            :telefono,:direccion,:municipio_id,:fecha_nacimiento,:user,
            :password,:foto,:rol,:estado,:created_at,:updated_at
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
            municipio_id = :municipio_id, fecha_nacimiento = :fecha_nacimiento, user = :user,  
            password = :password, foto = :foto, rol = :rol, estado = :estado, created_at = :created_at, 
            updated_at = :updated_at WHERE id = :id";
        return $this->save($query);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function deleted(): bool
    {
        $this->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $this->update();                    //Guarda los cambios..
    }

    /**
     * @param $query
     * @return Usuarios|array
     * @throws Exception
     */
    public static function search($query) : ?array
    {
        try {
            $arrUsuarios = array();
            $tmp = new Usuarios();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Usuario = new Usuarios($valor);
                    array_push($arrUsuarios, $Usuario);
                    unset($Usuario);
                }
                return $arrUsuarios;
            }
            return null;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    /**
     * @param int $id
     * @return Usuarios|null
     */
    public static function searchForId(int $id): ?Usuarios
    {
        try {
            if ($id > 0) {
                $tmpUsuario = new Usuarios();
                $tmpUsuario->Connect();
                $getrow = $tmpUsuario->getRow("SELECT * FROM weber.usuarios WHERE id =?", array($id));
                $tmpUsuario->Disconnect();
                return ($getrow) ? new Usuarios($getrow) : null;
            } else {
                throw new Exception('Id de usuario Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
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
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function nombresCompletos(): string
    {
        return $this->nombres . " " . $this->apellidos;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "Nombres: $this->nombres, 
                Apellidos: $this->nombres, 
                Tipo Documento: $this->tipo_documento, 
                Documento: $this->documento, 
                Telefono: $this->telefono, 
                Direccion: $this->direccion, 
                Direccion: $this->fecha_nacimiento->toDateTimeString()";
    }

    public function login($user, $password): Usuarios|String|null
    {

        try {
            $resultUsuarios = Usuarios::search("SELECT * FROM usuarios WHERE user = '$user'");
            /* @var $resultUsuarios Usuarios[] */
            if (!empty($resultUsuarios) && count($resultUsuarios) >= 1) {
                if (password_verify($password, $resultUsuarios[0]->getPassword())) {
                    if ($resultUsuarios[0]->getEstado() == 'Activo') {
                        return $resultUsuarios[0];
                    } else {
                        return "Usuario Inactivo";
                    }
                } else {
                    return "Contraseña Incorrecta";
                }
            } else {
                return "Usuario Incorrecto";
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
            return "Error en Servidor";
        }
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'nombres' => $this->getNombres(),
            'apellidos' => $this->getApellidos(),
            'tipo_documento' => $this->getTipoDocumento(),
            'documento' => $this->getDocumento(),
            'telefono' => $this->getTelefono(),
            'direccion' => $this->getDireccion(),
            'municipio_id' => $this->getMunicipioId(),
            'fecha_nacimiento' => $this->getFechaNacimiento()->toDateString(),
            'user' => $this->getUser(),
            'password' => $this->getPassword(),
            'foto' => $this->getFoto(),
            'rol' => $this->getRol(),
            'estado' => $this->getEstado(),
            'created_at' => $this->getCreatedAt()->toDateTimeString(),
            'updated_at' => $this->getUpdatedAt()->toDateTimeString(),
        ];
    }
}
