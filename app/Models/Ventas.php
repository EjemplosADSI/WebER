<?php


namespace App\Models;

require('BasicModel.php');


class Ventas extends BasicModel
{
    private $id;
    private $numero_serie;
    private $cliente_id;
    private $empleado_id;
    private $fecha_venta;
    private $monto;
    private $estado;

    /**
     * Ventas constructor.
     * @param $id
     * @param $numero_serie
     * @param $cliente_id
     * @param $empleado_id
     * @param $fecha_venta
     * @param $monto
     * @param $estado
     */
    public function __construct($venta = array())
    {
        parent::__construct();
        $this->id = $venta['id'] ?? null;
        $this->numero_serie = $venta['numero_serie'] ?? null;
        $this->cliente_id = $venta['cliente_id'] ?? null;
        $this->empleado_id = $venta['empleado_id'] ?? null;
        $this->fecha_venta = $venta['fecha_venta'] ?? null;
        $this->monto = $venta['monto'] ?? null;
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
    public function getNumeroSerie()
    {
        return $this->numero_serie;
    }

    /**
     * @param mixed $numero_serie
     */
    public function setNumeroSerie($numero_serie): void
    {
        $this->numero_serie = $numero_serie;
    }

    /**
     * @return Usuarios
     */
    public function getClienteId() : Usuarios
    {
        return $this->cliente_id;
    }

    /**
     * @param mixed $cliente_id
     */
    public function setClienteId(Usuarios $cliente_id): void
    {
        $this->cliente_id = $cliente_id;
    }

    /**
     * @return mixed
     */
    public function getEmpleadoId() : Usuarios
    {
        return $this->empleado_id;
    }

    /**
     * @param mixed $empleado_id
     */
    public function setEmpleadoId(Usuarios $empleado_id): void
    {
        $this->empleado_id = $empleado_id;
    }

    /**
     * @return mixed
     */
    public function getFechaVenta()
    {
        return $this->fecha_venta;
    }

    /**
     * @param mixed $fecha_venta
     */
    public function setFechaVenta($fecha_venta): void
    {
        $this->fecha_venta = $fecha_venta;
    }

    /**
     * @return mixed
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * @param mixed $monto
     */
    public function setMonto($monto): void
    {
        $this->monto = $monto;
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
     * @param $query
     * @return mixed
     */
    public static function search($query)
    {
        $arrVentas = array();
        $tmp = new Ventas();
        $getrows = $tmp->getRows($query);

        foreach ($getrows as $valor) {
            $Venta = new Ventas();
            $Venta->id = $valor['id'];
            $Venta->numero_serie = $valor['numero_serie'];
            $Venta->cliente_id = Usuarios::searchForId($valor['cliente_id']);
            $Venta->empleado_id = Usuarios::searchForId($valor['empleado_id']);
            $Venta->fecha_venta = $valor['fecha_venta'];
            $Venta->monto = $valor['monto'];
            $Venta->estado = $valor['estado'];
            $Venta->Disconnect();
            if(count($getrows) == 1){ // Si solamente hay un registro encontrado devuelve este objeto y no un array
                return $Venta;
            }
            array_push($arrVentas, $Venta);
        }
        $tmp->Disconnect();
        return $arrVentas;
    }

    /**
     * @return mixed
     */
    public static function getAll()
    {
        return Ventas::search("SELECT * FROM weber.ventas");
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id)
    {
        $Venta = null;
        if ($id > 0) {
            $Venta = new Ventas();
            $getrow = $Venta->getRow("SELECT * FROM weber.ventas WHERE id =?", array($id));
            $Venta->id = $getrow['id'];
            $Venta->numero_serie = $getrow['numero_serie'];
            $Venta->cliente_id = Usuarios::searchForId($getrow['cliente_id']);
            $Venta->empleado_id = Usuarios::searchForId($getrow['empleado_id']);
            $Venta->fecha_venta = $getrow['fecha_venta'];
            $Venta->monto = $getrow['monto'];
            $Venta->estado = $getrow['estado'];
        }
        $Venta->Disconnect();
        return $Venta;
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $result = $this->insertRow("INSERT INTO weber.ventas VALUES (NULL, ?, ?, ?, ?, ?, ?)", array(
                $this->numero_serie,
                $this->cliente_id->getId(),
                $this->empleado_id->getId(),
                $this->fecha_venta,
                $this->monto,
                $this->estado
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
        $result = $this->updateRow("UPDATE weber.ventas SET numero_serie = ?, cliente_id = ?, empleado_id = ?, fecha_venta = ?, monto = ?, estado = ? WHERE id = ?", array(
                $this->numero_serie,
                $this->cliente_id->getId(),
                $this->empleado_id->getId(),
                $this->fecha_venta,
                $this->monto,
                $this->estado,
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
        $Venta = Ventas::searchForId($id); //Buscando un usuario por el ID
        $Venta->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $Venta->update();                    //Guarda los cambios..
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "Numero Serie: $this->numero_serie, Cliente: $this->cliente_id->nombresCompletos(), Empleado: $this->empleado_id->nombresCompletos(), Fecha Venta: $this->fecha_venta, Monto: $this->monto, Estado: $this->estado";
    }

}