<?php

namespace App\Models;

use App\Interfaces\Model;
use Carbon\Carbon;
use Exception;
use JsonSerializable;

class DetalleVentas extends AbstractDBConnection implements Model, JsonSerializable
{
    private ?int $id;
    private int $ventas_id;
    private int $producto_id;
    private int $cantidad;
    private float $precio_venta;
    private Carbon $created_at;

    /* Relaciones */
    private ?Ventas $venta;
    private ?Productos $producto;

    /**
     * Detalle Venta constructor. Recibe un array asociativo
     * @param array $detalle_venta
     */
    public function __construct(array $detalle_venta = [])
    {
        parent::__construct();
        $this->setId($venta['id'] ?? NULL);
        $this->setPrecioVenta($detalle_venta['ventas_id'] ?? 0);
        $this->setProductoId($detalle_venta['producto_id'] ?? 0);
        $this->setCantidad($detalle_venta['cantidad'] ?? 0);
        $this->setPrecioVenta($detalle_venta['precio_venta'] ?? 0.0);
        $this->setCreatedAt(!empty($categoria['created_at']) ? Carbon::parse($categoria['created_at']) : new Carbon());
    }

    /**
     *
     */
    function __destruct()
    {
        $this->Disconnect();
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
     * @return int|mixed
     */
    public function getVentasId() : int
    {
        return $this->ventas_id;
    }

    /**
     * @param int|mixed $ventas_id
     */
    public function setVentasId(int $ventas_id): void
    {
        $this->ventas_id = $ventas_id;
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
     * @return int|mixed
     */
    public function getCantidad() : int
    {
        return $this->cantidad;
    }

    /**
     * @param int|mixed $cantidad
     */
    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return float|mixed
     */
    public function getPrecioVenta() : float
    {
        return $this->precio_venta;
    }

    /**
     * @param float|mixed $precio_venta
     */
    public function setPrecioVenta(float $precio_venta): void
    {
        $this->precio_venta = $precio_venta;
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

    /* Relaciones */
    /**
     * Retorna el objeto venta correspondiente al detalle venta
     * @return Ventas|null
     */
    public function getVenta(): ?Ventas
    {
        if(!empty($this->ventas_id)){
            $this->venta = Ventas::searchForId($this->ventas_id) ?? new Ventas();
            return $this->venta;
        }
        return NULL;
    }

    /**
     * Retorna el objeto producto correspondiente al detalle venta
     * @return Productos|null
     */
    public function getProducto(): ?Productos
    {
        if(!empty($this->producto_id)){
            $this->producto = Productos::searchForId($this->producto_id) ?? new Productos();
            return $this->producto;
        }
        return NULL;
    }

    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>   $this->getId(),
            ':venta_id' =>   $this->getVentasId(),
            ':producto_id' =>  $this->getProductoId(),
            ':cantidad' =>   $this->getCantidad(),
            ':precio_venta' =>   $this->getPrecioVenta(),
            ':created_at' =>  $this->getCreatedAt()->toDateTimeString(), //YYYY-MM-DD HH:MM:SS
        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert()
    {
        $query = "INSERT INTO weber.detalle_ventas VALUES (:id,:venta_id,:producto_id,:cantidad,:precio_venta,:created_at)";
        return $this->save($query);
    }

    /**
     * @return mixed
     */
    public function update() : bool
    {
        $query = "UPDATE weber.detalle_ventas SET 
            cantidad = :cantidad, precio_venta = :precio_venta,
            created_at = :created_at WHERE id = :id";
        return $this->save($query);
    }

    /**
     * @return mixed
     */
    public function deleted() : bool
    {
        $query = "DELETE FROM detalle_ventas WHERE id = :id";
        return $this->save($query);
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function search($query) : ?array
    {
        try {
            $arrDetalleVenta = array();
            $tmp = new DetalleVentas();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $DetalleVenta = new DetalleVentas($valor);
                array_push($arrDetalleVenta, $DetalleVenta);
                unset($DetalleVenta);
            }
            return $arrDetalleVenta;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id) : ?DetalleVentas
    {
        try {
            if ($id > 0) {
                $DetalleVenta = new DetalleVentas();
                $DetalleVenta->Connect();
                $getrow = $DetalleVenta->getRow("SELECT * FROM weber.detalle_ventas WHERE id = ?", array($id));
                $DetalleVenta->Disconnect();
                return ($getrow) ? new DetalleVentas($getrow) : null;
            }else{
                throw new Exception('Id de detalle venta Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    /**
     * @return mixed
     */
    public static function getAll() : array
    {
        return DetalleVentas::search("SELECT * FROM weber.detalle_ventas");
    }

    /**
     * @param $venta_id
     * @param $producto_id
     * @return bool
     */
    public static function productoEnFactura($venta_id,$producto_id): bool
    {
        $result = DetalleVentas::search("SELECT id FROM weber.detalle_ventas where venta_id = '" . $venta_id. "' and producto_id = '" . $producto_id. "'");
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return "Venta: ".$this->venta->getNumeroSerie().", Producto: ".$this->producto->getNombre().", Cantidad: $this->cantidad, Precio Venta: $this->precio_venta";
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
            'venta_id' => $this->getVenta()->jsonSerialize(),
            'producto_id' => $this->getProducto()->jsonSerialize(),
            'cantidad' => $this->getCantidad(),
            'precio_venta' => $this->getPrecioVenta(),
            'created_at' => $this->getCreatedAt()->toDateTimeString(),
        ];
    }
}