<?php

namespace App\Models;

use JsonSerializable;

require_once (__DIR__ .'/../../vendor/autoload.php');
require_once('BasicModel.php');

class Productos extends BasicModel implements JsonSerializable
{
    private int $id;
    private string $nombres;
    private float $precio;
    private float $porcentaje_ganancia;
    private int $stock;
    private string $estado;

    /**
     * Producto constructor.
     * @param int $id
     * @param string $nombres
     * @param float $precio
     * @param float $porcentaje_ganancia
     * @param int $stock
     * @param string $estado
     */
    public function __construct($venta = array())
    {
        parent::__construct();
        $this->id = $venta['id'] ?? 0;
        $this->nombres = $venta['nombres'] ?? '';
        $this->precio = $venta['precio'] ?? 0.0;
        $this->porcentaje_ganancia = $venta['porcentaje_ganancia'] ?? 0.0;
        $this->stock = $venta['stock'] ?? 0;
        $this->estado = $venta['estado'] ?? '';
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
        $this->nombres = $nombres;
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
     * @return bool
     * @throws \Exception
     */
    public function create() : bool
    {
        $result = $this->insertRow("INSERT INTO weber.productos VALUES (NULL, ?, ?, ?, ?, ?)", array(
                $this->nombres,
                $this->precio,
                $this->porcentaje_ganancia,
                $this->stock,
                $this->estado
            )
        );
        $this->Disconnect();
        return $result;
    }

    /**
     * @return bool
     */
    public function update() : bool
    {
        $result = $this->updateRow("UPDATE weber.productos SET nombres = ?, precio = ?, porcentaje_ganancia = ?, stock = ?, estado = ? WHERE id = ?", array(
                $this->nombres,
                $this->precio,
                $this->porcentaje_ganancia,
                $this->stock,
                $this->estado,
                $this->id
            )
        );
        $this->Disconnect();
        return $result;
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function deleted($id) : bool
    {
        $Producto = Productos::searchForId($id); //Buscando un usuario por el ID
        $Producto->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $Producto->update();                    //Guarda los cambios..
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function search($query) : array
    {
        $arrProductos = array();
        $tmp = new Productos();
        $getrows = $tmp->getRows($query);

        foreach ($getrows as $valor) {
            $Producto = new Productos();
            $Producto->id = $valor['id'];
            $Producto->nombres = $valor['nombres'];
            $Producto->precio = $valor['precio'];
            $Producto->porcentaje_ganancia = $valor['porcentaje_ganancia'];
            $Producto->stock = $valor['stock'];
            $Producto->estado = $valor['estado'];
            $Producto->Disconnect();
            array_push($arrProductos, $Producto);
        }
        $tmp->Disconnect();
        return $arrProductos;
    }

    /**
     * @param $id
     * @return Productos
     * @throws \Exception
     */
    public static function searchForId($id) : Productos
    {
        $Producto = null;
        if ($id > 0) {
            $Producto = new Productos();
            $getrow = $Producto->getRow("SELECT * FROM weber.productos WHERE id =?", array($id));
            $Producto->id = $getrow['id'];
            $Producto->nombres = $getrow['nombres'];
            $Producto->precio = $getrow['precio'];
            $Producto->porcentaje_ganancia = $getrow['porcentaje_ganancia'];
            $Producto->stock = $getrow['stock'];
            $Producto->estado = $getrow['estado'];
        }
        $Producto->Disconnect();
        return $Producto;
    }

    /**
     * @return Productos|array|mixed
     */
    public static function getAll() : array
    {
        return Productos::search("SELECT * FROM weber.productos");
    }

    /**
     * @param $nombres
     * @return bool
     */
    public static function productoRegistrado($nombres): bool
    {
        $result = Productos::search("SELECT id FROM weber.productos where nombres = '" . $nombres. "'");
        if (count($result) > 0) {
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
        return "Nombre: $this->nombres, Precio: $this->precio, Porcentaje: $this->porcentaje_ganancia, Stock: $this->stock, Estado: $this->estado";
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
            'nombres' => $this->getNombres(),
            'precio' => $this->getPrecio(),
            'porcentaje_ganancias' => $this->getPorcentajeGanancia(),
            'precio_venta' => $this->getPrecioVenta(),
            'stock' => $this->getStock(),
            'estado' => $this->getEstado(),
        ];
    }
}