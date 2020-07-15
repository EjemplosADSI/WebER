<?php


namespace App\Models;

require_once('BasicModel.php');

class DetalleVentas extends BasicModel
{
    private $id;
    private $ventas_id;
    private $producto_id;
    private $cantidad;
    private $precio_venta;

    /**
     * DetalleVentas constructor.
     * @param $id
     * @param $ventas_id
     * @param $producto_id
     * @param $cantidad
     * @param $precio_venta
     */
    public function __construct($venta = array())
    {
        parent::__construct();
        $this->id = $venta['id'] ?? null;
        $this->nombres = $venta['nombres'] ?? null;
        $this->precio = $venta['precio'] ?? null;
        $this->stock = $venta['stock'] ?? null;
        $this->estado = $venta['estado'] ?? null;
    }

    /**
     *
     */
    function __destruct()
    {
        $this->Disconnect();
    }

    /**
     * @return mixed|null
     */
    public function getId(): ?mixed
    {
        return $this->id;
    }

    /**
     * @param mixed|null $id
     */
    public function setId(?mixed $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getVentasId() : Ventas
    {
        return $this->ventas_id;
    }

    /**
     * @param mixed $ventas_id
     */
    public function setVentasId(Ventas $ventas_id): void
    {
        $this->ventas_id = $ventas_id;
    }

    /**
     * @return mixed
     */
    public function getProductoId() : Productos
    {
        return $this->producto_id;
    }

    /**
     * @param mixed $producto_id
     */
    public function setProductoId(Productos $producto_id): void
    {
        $this->producto_id = $producto_id;
    }

    /**
     * @return mixed
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     */
    public function setCantidad($cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return mixed
     */
    public function getPrecioVenta()
    {
        return $this->precio_venta;
    }

    /**
     * @param mixed $precio_venta
     */
    public function setPrecioVenta($precio_venta): void
    {
        $this->precio_venta = $precio_venta;
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function search($query)
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
     * @return mixed
     */
    public static function getAll()
    {
        return DetalleVentas::search("SELECT * FROM weber.detalle_ventas");
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id)
    {
        $DetalleVenta = null;
        if ($id > 0) {
            $DetalleVenta = new DetalleVentas();
            $getrow = $DetalleVenta->getRow("SELECT * FROM weber.detalle_venta WHERE id =?", array($id));
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
    public function create()
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
    public function update()
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
    public function deleted($id)
    {
        $DetalleVenta = DetalleVentas::searchForId($id); //Buscando un usuario por el ID
        $deleterow = $DetalleVenta->deleteRow("DELETE FROM detalle_venta WHERE id = ?", array($id));
        return $deleterow;                    //Guarda los cambios..
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
    public function __toString()
    {
        return "Venta: $this->ventas_id->getNumeroSerie(), Producto: $this->producto_id->getNombres(), Cantidad: $this->cantidad, Precio Venta: $this->precio_venta";
    }
}