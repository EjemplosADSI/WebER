<?php

namespace App\Models;

require_once (__DIR__ .'/../../vendor/autoload.php');
require_once ('Usuarios.php');
require_once('BasicModel.php');

use Carbon\Carbon;
use App\Models\Usuarios;

class Ventas extends BasicModel
{
    private int $id;
    private string $numero_serie;
    private ?Usuarios $cliente_id;
    private ?Usuarios $empleado_id;
    private Carbon $fecha_venta;
    private float $monto;
    private string $estado;

    /**
     * Ventas constructor.
     * @param int $id
     * @param string $numero_serie
     * @param Usuarios $cliente_id
     * @param Usuarios $empleado_id
     * @param Carbon $fecha_venta
     * @param float $monto
     * @param string $estado
     */
    public function __construct($venta = array())
    {
        parent::__construct();
        $this->id = $venta['id'] ?? 0;
        $this->numero_serie = $venta['numero_serie'] ?? '';
        $this->cliente_id = $venta['cliente_id'] ?? null;
        $this->empleado_id = $venta['empleado_id'] ?? null;
        $this->fecha_venta = $venta['fecha_venta'] ?? new Carbon();
        $this->monto = $venta['monto'] ?? 0.0;
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
    public function getNumeroSerie() : string
    {
        return $this->numero_serie;
    }

    /**
     * @param mixed|string $numero_serie
     */
    public function setNumeroSerie(string $numero_serie): void
    {
        $this->numero_serie = $numero_serie;
    }

    /**
     * @return Usuarios|mixed|null
     */
    public function getClienteId() : Usuarios
    {
        return $this->cliente_id;
    }

    /**
     * @param Usuarios|mixed|null $cliente_id
     */
    public function setClienteId(Usuarios $cliente_id): void
    {
        $this->cliente_id = $cliente_id;
    }

    /**
     * @return Usuarios|mixed|null
     */
    public function getEmpleadoId() : Usuarios
    {
        return $this->empleado_id;
    }

    /**
     * @param Usuarios|mixed|null $empleado_id
     */
    public function setEmpleadoId(Usuarios $empleado_id): void
    {
        $this->empleado_id = $empleado_id;
    }

    /**
     * @return Carbon|mixed
     */
    public function getFechaVenta() : Carbon
    {
        return $this->fecha_venta->locale('es');
    }

    /**
     * @param Carbon|mixed $fecha_venta
     */
    public function setFechaVenta(Carbon $fecha_venta): void
    {
        $this->fecha_venta = $fecha_venta;
    }

    /**
     * @return float|mixed
     */
    public function getMonto() : float
    {
        return $this->monto;
    }

    /**
     * @param float|mixed $monto
     */
    public function setMonto(float $monto): void
    {
        $this->monto = $monto;
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
    public function setEstado(float $estado): void
    {
        $this->estado = $estado;
    }

    /**
     * @return mixed
     */
    public function create() : bool
    {
        $result = $this->insertRow("INSERT INTO weber.ventas VALUES (NULL, ?, ?, ?, ?, ?, ?)", array(
                $this->numero_serie,
                $this->cliente_id->getId(),
                $this->empleado_id->getId(),
                $this->fecha_venta->toDateTimeString(), //YYYY-MM-DD HH:MM:SS
                $this->monto,
                $this->estado
            )
        );
        $this->setId(($result) ? $this->getLastId() : null);
        $this->Disconnect();
        return $result;
    }

    /**
     * @return mixed
     */
    public function update() : bool
    {
        $result = $this->updateRow("UPDATE weber.ventas SET numero_serie = ?, cliente_id = ?, empleado_id = ?, fecha_venta = ?, monto = ?, estado = ? WHERE id = ?", array(
                $this->numero_serie,
                $this->cliente_id->getId(),
                $this->empleado_id->getId(),
                $this->fecha_venta->toDateTimeString(),
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
    public function deleted($id) : bool
    {
        $Venta = Ventas::searchForId($id); //Buscando un usuario por el ID
        $Venta->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $Venta->update();                    //Guarda los cambios..
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function search($query) : array
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
            $Venta->fecha_venta = Carbon::parse($valor['fecha_venta']);
            $Venta->monto = $valor['monto'];
            $Venta->estado = $valor['estado'];
            $Venta->Disconnect();
            array_push($arrVentas, $Venta);
        }

        $tmp->Disconnect();
        return $arrVentas;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id) : Ventas
    {
        $Venta = null;
        if ($id > 0) {
            $Venta = new Ventas();
            $getrow = $Venta->getRow("SELECT * FROM weber.ventas WHERE id =?", array($id));
            $Venta->id = $getrow['id'];
            $Venta->numero_serie = $getrow['numero_serie'];
            $Venta->cliente_id = Usuarios::searchForId($getrow['cliente_id']);
            $Venta->empleado_id = Usuarios::searchForId($getrow['empleado_id']);
            $Venta->fecha_venta = Carbon::parse($getrow['fecha_venta']);
            $Venta->monto = $getrow['monto'];
            $Venta->estado = $getrow['estado'];
        }
        $Venta->Disconnect();
        return $Venta;
    }

    /**
     * @return mixed
     */
    public static function getAll() : array
    {
        return Ventas::search("SELECT * FROM weber.ventas");
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return "Numero Serie: $this->numero_serie, Cliente: $this->cliente_id->nombresCompletos(), Empleado: $this->empleado_id->nombresCompletos(), Fecha Venta: $this->fecha_venta->toDateTimeString(), Monto: $this->monto, Estado: $this->estado";
    }

}