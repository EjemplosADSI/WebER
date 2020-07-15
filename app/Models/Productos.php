<?php


namespace App\Models;

require('BasicModel.php');

class Productos extends BasicModel
{
    private $id;
    private $nombres;
    private $precio;
    private $stock;
    private $estado;

    /**
     * Producto constructor.
     * @param $id
     * @param $nombres
     * @param $precio
     * @param $stock
     * @param $estado
     * @param $DetalleVenta
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
    public function getId(): int
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
    public function getNombres() : string
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
    public function getPrecio() : float
    {
        return $this->precio;
    }

    /**
     * @param mixed $precio
     */
    public function setPrecio($precio): void
    {
        $this->precio = $precio;
    }

    /**
     * @return mixed
     */
    public function getStock() : int
    {
        return $this->stock;
    }

    /**
     * @param mixed $stock
     */
    public function setStock($stock): void
    {
        $this->stock = $stock;
    }

    /**
     * @return mixed
     */
    public function getEstado() : string 
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
     * @param $query
     * @return mixed
     */
    public static function search($query)
    {
        $arrProductos = array();
        $tmp = new Productos();
        $getrows = $tmp->getRows($query);

        foreach ($getrows as $valor) {
            $Producto = new Productos();
            $Producto->id = $valor['id'];
            $Producto->nombres = $valor['nombres'];
            $Producto->precio = $valor['precio'];
            $Producto->stock = $valor['stock'];
            $Producto->estado = $valor['estado'];
            $Producto->Disconnect();
            array_push($arrProductos, $Producto);
        }
        $tmp->Disconnect();
        return $arrProductos;
    }

    /**
     * @return Productos|array|mixed
     */
    public static function getAll()
    {
        return Productos::search("SELECT * FROM weber.productos");
    }

    /**
     * @param $id
     * @return Productos|null
     * @throws \Exception
     */
    public static function searchForId($id)
    {
        $Producto = null;
        if ($id > 0) {
            $Producto = new Productos();
            $getrow = $Producto->getRow("SELECT * FROM weber.productos WHERE id =?", array($id));
            $Producto->id = $getrow['id'];
            $Producto->nombres = $getrow['nombres'];
            $Producto->precio = $getrow['precio'];
            $Producto->stock = $getrow['stock'];
            $Producto->estado = $getrow['estado'];
        }
        $Producto->Disconnect();
        return $Producto;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function create() : bool
    {
        $result = $this->insertRow("INSERT INTO weber.productos VALUES (NULL, ?, ?, ?, ?)", array(
                $this->nombres,
                $this->precio,
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
    public function update()
    {
        $result = $this->updateRow("UPDATE weber.productos SET nombres = ?, precio = ?, stock = ?, estado = ? WHERE id = ?", array(
                $this->nombres,
                $this->precio,
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
    public function deleted($id)
    {
        $Producto = Productos::searchForId($id); //Buscando un usuario por el ID
        $Producto->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $Producto->update();                    //Guarda los cambios..
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
     * @return string
     */
    public function __toString()
    {
        return "Nombre: $this->nombres, Precio: $this->precio, Stock: $this->stock, Estado: $this->estado";
    }

}