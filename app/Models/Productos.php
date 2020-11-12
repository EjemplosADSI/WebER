<?php

namespace App\Models;

use App\Models\Interfaces\Model;
use Carbon\Carbon;
use Exception;
use JsonSerializable;


class Productos extends AbstractDBConnection implements Model, JsonSerializable
{
    private ?int $id;
    private string $nombre;
    private float $precio;
    private float $porcentaje_ganancia;
    private int $stock;
    private string $estado;
    private Carbon $created_at;
    private Carbon $updated_at;

    /* Relaciones */
    private ?array $fotosProducto;

    /**
     * Producto constructor. Recibe un array asociativo
     * @param array $producto
     */
    public function __construct(array $producto = [])
    {
        parent::__construct();
        $this->setId($producto['id'] ?? NULL);
        $this->setNombre($producto['nombre'] ?? '');
        $this->setPrecio($producto['precio'] ?? 0.0);
        $this->setPorcentajeGanancia($producto['porcentaje_ganancia'] ?? 0.0);
        $this->setStock($producto['stock'] ?? 0);
        $this->setEstado($producto['estado'] ?? '');
        $this->setCreatedAt(!empty($producto['created_at']) ? Carbon::parse($producto['created_at']) : new Carbon());
        $this->setUpdatedAt(!empty($producto['updated_at']) ? Carbon::parse($producto['updated_at']) : new Carbon());
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
    public function getNombre() : string
    {
        return ucfirst($this->nombre);
    }

    /**
     * @param mixed|string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = trim(strtolower($nombre));
    }

    /**
     * @return float|mixed
     */
    public function getPrecio() : float
    {
        return $this->precio;
    }

    /**
     * @param float|mixed $precio
     */
    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;
    }

    /**
     * @return float|mixed
     */
    public function getPorcentajeGanancia() : float
    {
        return $this->porcentaje_ganancia;
    }

    /**
     * @param float|mixed $porcentaje_ganancia
     */
    public function setPorcentajeGanancia(float $porcentaje_ganancia): void
    {
        $this->porcentaje_ganancia = $porcentaje_ganancia;
    }

    /**
     * @return int|mixed
     */
    public function getStock() : int
    {
        return $this->stock;
    }

    /**
     * @param int|mixed $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
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
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
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
        return $this->updated_at;
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
     * retorna un array de fotos que pertenecen al producto
     * @return array
     */
    public function getFotosProducto(): ?array
    {
        $this->fotosProducto = Fotos::search("SELECT * FROM weber.fotos WHERE productos_id = ".$this->id);
        return $this->fotosProducto;
    }

    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':nombre' =>   $this->getNombre(),
            ':precio' =>   $this->getPrecio(),
            ':porcentaje_ganancia' =>  $this->getPorcentajeGanancia(),
            ':stock' =>   $this->getStock(),
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
        $query = "INSERT INTO weber.productos VALUES (:id,:nombre,:precio,:porcentaje_ganancia,:stock,:estado,:created_at,:updated_at)";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    public function update(): ?bool
    {
        $query = "UPDATE weber.productos SET 
            nombre = :nombre, precio = :precio, porcentaje_ganancia = :porcentaje_ganancia, 
            stock = :stock, estado = :estado, created_at = :created_at, 
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
            $arrProductos = array();
            $tmp = new Productos();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Producto = new Productos($valor);
                array_push($arrProductos, $Producto);
                unset($Producto);
            }
            return $arrProductos;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    /**
     * @param $id
     * @return Productos
     * @throws Exception
     */
    public static function searchForId($id) : ?Productos
    {
        try {
            if ($id > 0) {
                $Producto = new Productos();
                $Producto->Connect();
                $getrow = $Producto->getRow("SELECT * FROM weber.productos WHERE id =?", array($id));
                $Producto->Disconnect();
                return ($getrow) ? new Productos($getrow) : null;
            }else{
                throw new Exception('Id de usuario Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function getAll() : array
    {
        return Productos::search("SELECT * FROM weber.productos");
    }

    /**
     * @param $nombre
     * @return bool
     * @throws Exception
     */
    public static function productoRegistrado($nombre): bool
    {
        $nombre = trim(strtolower($nombre));
        $result = Productos::search("SELECT id FROM weber.productos where nombre = '" . $nombre. "'");
        if ( !empty($result) && count ($result) > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return float|mixed
     */
    public function getPrecioVenta() : float
    {
        return $this->precio + ($this->precio * ($this->porcentaje_ganancia / 100));
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return "Nombre: $this->nombre, Precio: $this->precio, Porcentaje: $this->porcentaje_ganancia, Stock: $this->stock, Estado: $this->estado";
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
            'precio' => $this->getPrecio(),
            'porcentaje_ganancias' => $this->getPorcentajeGanancia(),
            'precio_venta' => $this->getPrecioVenta(),
            'stock' => $this->getStock(),
            'estado' => $this->getEstado(),
        ];
    }
}