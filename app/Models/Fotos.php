<?php

namespace App\Models;

use App\Models\Interfaces\Model;
use Carbon\Carbon;
use Exception;
use JsonSerializable;


class Fotos extends AbstractDBConnection implements Model, JsonSerializable
{
    private ?int $id;
    private ?string $nombre;
    private ?string $descripcion;
    private int $producto_id;
    private string $ruta;
    private string $estado;
    private Carbon $created_at;
    private Carbon $updated_at;

    /* Relaciones */
    private Productos $producto;

    /**
     * Fotos constructor.
     * @param array $foto
     */
    public function __construct(array $foto = [])
    {
        parent::__construct();
        $this->setId($foto['id'] ?? NULL);
        $this->setNombre($foto['nombre'] ?? NULL);
        $this->setDescripcion($foto['descripcion'] ?? NULL);
        $this->setProductoId($foto['producto_id'] ?? 0);
        $this->setRuta($foto['ruta'] ?? '');
        $this->setEstado($foto['estado'] ?? 'Activo');
        $this->setCreatedAt(!empty($foto['created_at']) ? Carbon::parse($foto['created_at']) : new Carbon());
        $this->setUpdatedAt(!empty($foto['updated_at']) ? Carbon::parse($foto['updated_at']) : new Carbon());
    }

    function __destruct()
    {
        if($this->isConnected){
            $this->Disconnect();
        }
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
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
     * @return string|null
     */
    public function getNombre(): ?string
    {
        return ucwords($this->nombre);
    }

    /**
     * @param string|null $nombre
     */
    public function setNombre(?string $nombre): void
    {
        $this->nombre = trim(mb_strtolower($nombre, 'UTF-8'));
    }

    /**
     * @return string|null
     */
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    /**
     * @param string|null $descripcion
     */
    public function setDescripcion(?string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return int
     */
    public function getProductoId(): int
    {
        return $this->producto_id;
    }

    /**
     * @param int $producto_id
     */
    public function setProductoId(int $producto_id): void
    {
        $this->producto_id = $producto_id;
    }

    /**
     * @return string
     */
    public function getRuta(): string
    {
        return $this->ruta;
    }

    /**
     * @param string $ruta
     */
    public function setRuta(string $ruta): void
    {
        $this->ruta = $ruta;
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
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->created_at->locale('es');
    }

    /**
     * @param Carbon $created_at
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

    /**
     * @return Productos
     * @throws Exception
     */
    public function getProducto(): ?Productos
    {
        if(!empty($this->producto_id)){
            $this->producto = Productos::searchForId($this->producto_id) ?? new Productos();
            return $this->producto;
        }
        return null;
    }

    /**
     * @param Productos $producto
     */
    public function setProducto(Productos $producto): void
    {
        $this->producto = $producto;
    }

    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':nombre' =>   $this->getNombre(),
            ':descripcion' =>   $this->getDescripcion(),
            ':producto_id' =>   $this->getProductoId(),
            ':ruta' =>  $this->getRuta(),
            ':estado' =>   $this->getEstado(),
            ':created_at' =>  $this->getCreatedAt()->toDateTimeString(), //YYYY-MM-DD HH:MM:SS
            ':updated_at' =>  $this->getUpdatedAt()->toDateTimeString() //YYYY-MM-DD HH:MM:SS
        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    /**
     * @return bool|null
     */
    function insert(): ?bool
    {
        $query = "INSERT INTO weber.fotos VALUES (:id, :nombre, :descripcion, :producto_id, :ruta, :estado, :created_at, :updated_at)";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    function update(): ?bool
    {
        $query = "UPDATE weber.fotos SET 
            nombre = :nombre, descripcion = :descripcion, producto_id = :producto_id, 
            ruta = :ruta, estado = :estado, created_at = :created_at, 
            updated_at = :updated_at WHERE id = :id";
        return $this->save($query);
    }

    /**
     * @return bool
     * @throws Exception
     */
    function deleted() : bool
    {
        $this->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $this->update();                    //Guarda los cambios..
    }

    /**
     * @param $query
     * @return Usuarios|array
     * @throws Exception
     */
    static function search($query): ?array
    {
        try {
            $arrFotos = array();
            $tmp = new Fotos();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Foto = new Fotos($valor);
                array_push($arrFotos, $Foto);
                unset($Foto);
            }
            return $arrFotos;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    /**
     * @return array
     * @throws Exception
     */
    static function getAll(): ?array
    {
        return Fotos::search("SELECT * FROM weber.fotos");
    }

    static function searchForId(int $id): ?object
    {
        try {
            if ($id > 0) {
                $Foto = new Fotos();
                $Foto->Connect();
                $getrow = $Foto->getRow("SELECT * FROM weber.fotos WHERE id =?", array($id));
                $Foto->Disconnect();
                return ($getrow) ? new Fotos($getrow) : null;
            }else{
                throw new Exception('Id de foto Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return "Nombre: $this->nombre, Descripcion: $this->descripcion, producto_id: $this->producto_id, Ruta: $this->ruta, Estado: $this->estado";
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
            'nombre' => $this->getNombre(),
            'descripcion' => $this->getDescripcion(),
            'producto_id' => $this->getProductoId(),
            'ruta' => $this->getRuta(),
            'estado' => $this->getEstado(),
        ];
    }
}