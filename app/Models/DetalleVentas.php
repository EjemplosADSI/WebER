<?php

namespace App\Models;

require_once (__DIR__ .'/../../vendor/autoload.php');
require_once('BasicModel.php');

class DetalleVentas extends BasicModel
{
    private int $id;
    private Ventas $ventas_id;
    private Productos $producto_id;
    private int $cantidad;
    private float $precio_venta;

    /**
     * DetalleVentas constructor.
     * @param int $id
     * @param Ventas $ventas_id
     * @param Productos $producto_id
     * @param int $cantidad
     * @param float $precio_venta
     */
    public function __construct($venta = array())
    {
        parent::__construct();
        $this->id = $venta['id'] ?? 0;
        $this->ventas_id = $venta['ventas_id'] ?? new Ventas();
        $this->producto_id = $venta['producto_id'] ?? new Productos();
        $this->cantidad = $venta['cantidad'] ?? 0;
        $this->precio_venta = $venta['precio_venta'] ?? 0.0;
    }

    /**
     *
     */
    function __destruct()
    {
        $this->Disconnect();
    }

    /**
     * @return int|mixed
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @param int|mixed $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Ventas|mixed
     */
    public function getVentasId() : Ventas
    {
        return $this->ventas_id;
    }

    /**
     * @param Ventas|mixed $ventas_id
     */
    public function setVentasId(Ventas $ventas_id): void
    {
        $this->ventas_id = $ventas_id;
    }

    /**
     * @return Productos
     */
    public function getProductoId(): Productos
    {
        return $this->producto_id;
    }

    /**
     * @param Productos $producto_id
     */
    public function setProductoId(Productos $producto_id): void
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
     * @return mixed
     */
    public function create() : bool
    {
        $result = $this->insertRow("INSERT INTO weber.detalle_venta VALUES (NULL, ?, ?, ?, ?)", array(
                $this->ventas_id->getId(),
                $this->producto_id->getId(),
                $this->cantidad,
                $this->precio_venta
            )
        );
        $this->Disconnect();
        return $result;
    }

    /**
     * @return mixed
     */
    public function update() : bool
    {
        $result = $this->updateRow("UPDATE weber.detalle_venta SET ventas_id = ?, producto_id = ?, cantidad = ?, precio_venta = ? WHERE id = ?", array(
                $this->ventas_id->getId(),
                $this->producto_id->getId(),
                $this->cantidad,
                $this->precio_venta,
                $this->id
            )
        );
        $this->Disconnect();
        return $result;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleted($id) : bool
    {
        $DetalleVenta = DetalleVentas::searchForId($id); //Buscando un usuario por el ID
        $deleterow = $DetalleVenta->deleteRow("DELETE FROM detalle_venta WHERE id = ?", array($id));
        return $deleterow;                    //Guarda los cambios..
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function search($query) : array
    {
        $arrDetalleVenta = array();
        $tmp = new DetalleVentas();
        $getrows = $tmp->getRows($query);

        foreach ($getrows as $valor) {
            $DetalleVenta = new DetalleVentas();
            $DetalleVenta->id = $valor['id'];
            $DetalleVenta->ventas_id = Ventas::searchForId($valor['ventas_id']);
            $DetalleVenta->producto_id = Productos::searchForId($valor['producto_id']);
            $DetalleVenta->cantidad = $valor['cantidad'];
            $DetalleVenta->precio_venta = $valor['precio_venta'];
            $DetalleVenta->Disconnect();
            if(count($getrows) == 1){ // Si solamente hay un registro encontrado devuelve este objeto y no un array
                return $DetalleVenta;
            }
            array_push($arrDetalleVenta, $DetalleVenta);
        }
        $tmp->Disconnect();
        return $arrDetalleVenta;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id) : DetalleVentas
    {
        $DetalleVenta = null;
        if ($id > 0) {
            $DetalleVenta = new DetalleVentas();
            $getrow = $DetalleVenta->getRow("SELECT * FROM weber.detalle_ventas WHERE id =?", array($id));
            $DetalleVenta->id = $getrow['id'];
            $DetalleVenta->ventas_id = Ventas::searchForId($getrow['ventas_id']);
            $DetalleVenta->producto_id = Productos::searchForId($getrow['producto_id']);
            $DetalleVenta->cantidad = $getrow['cantidad'];
            $DetalleVenta->precio_venta = $getrow['precio_venta'];
        }
        $DetalleVenta->Disconnect();
        return $DetalleVenta;
    }

    /**
     * @return mixed
     */
    public static function getAll() : array
    {
        return DetalleVentas::search("SELECT * FROM weber.detalle_ventas");
    }

    /**
     * @param $nombres
     * @return bool
     */
    public static function productoEnFactura($producto_id): bool
    {
        $result = DetalleVentas::search("SELECT id FROM weber.detalle_venta where producto_id = '" . $producto_id. "'");
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
        return "Venta: $this->ventas_id->getNumeroSerie(), Producto: $this->producto_id->getNombres(), Cantidad: $this->cantidad, Precio Venta: $this->precio_venta";
    }
}