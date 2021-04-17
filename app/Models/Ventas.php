<?php

namespace App\Models;

use App\Interfaces\Model;
use Carbon\Carbon;
use Exception;
use JsonSerializable;

class Ventas extends AbstractDBConnection implements Model
{
    private ?int $id;
    private string $numero_serie;
    private int $cliente_id; // Id numerico (1,2,3,4) almacena en BD
    private int $empleado_id;
    private Carbon $fecha_venta;
    private float $monto;
    private string $estado;
    private Carbon $created_at;
    private Carbon $updated_at;

    /* Relaciones */
    private ?Usuarios $cliente; // Objeto de cliente (Nombres, Telefono, direccion)
    private ?Usuarios $empleado;
    private ?array $detalleVenta;

    /**
     * Venta constructor. Recibe un array asociativo
     * @param array $venta
     */
    public function __construct(array $venta = [])
    {
        parent::__construct();
        $this->setId($venta['id'] ?? NULL);
        $this->setNumeroSerie($venta['numero_serie'] ?? NULL);
        $this->setClienteId($venta['cliente_id'] ?? 0);
        $this->setEmpleadoId($venta['empleado_id'] ?? 0);
        $this->setFechaVenta(!empty($venta['created_at']) ? Carbon::parse($venta['created_at']) : new Carbon());
        $this->setEstado($venta['estado'] ?? 'En progreso');
        $this->setCreatedAt(!empty($venta['created_at']) ? Carbon::parse($venta['created_at']) : new Carbon());
        $this->setUpdatedAt(!empty($venta['updated_at']) ? Carbon::parse($venta['updated_at']) : new Carbon());
        $this->setMonto();
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
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @param int|mixed $id
     */
    public function setId(?int $id): void
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
     * @param
     * @throws Exception
     */
    public function setNumeroSerie(string $numero_serie = null): void
    {
        if(empty($numero_serie)){
            $this->Connect();
            $this->numero_serie = 'FV-'.($this->countRowsTable('ventas')+1).'-'.date('Y-m-d');
            $this->Disconnect();
        }else{
            $this->numero_serie = $numero_serie;
        }
    }

    /**
     * @return int
     */
    public function getClienteId() : int
    {
        return $this->cliente_id;
    }

    /**
     * @param int $cliente_id
     */
    public function setClienteId(int $cliente_id): void
    {
        $this->cliente_id = $cliente_id;
    }

    /**
     * @return int
     */
    public function getEmpleadoId() : int
    {
        return $this->empleado_id;
    }

    /**
     * @param int $empleado_id
     */
    public function setEmpleadoId(int $empleado_id): void
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
    public function setMonto(): void
    {
        $total = 0;
        if($this->getId() != null){
            $arrDetallesVenta = $this->getDetalleVenta();
            if(!empty($arrDetallesVenta)){
                /* @var $arrDetallesVenta DetalleVentas[] */
                foreach ($arrDetallesVenta as $DetalleVenta){
                    $total += $DetalleVenta->getTotalProducto();
                }
            }
        }
        $this->monto = $total;
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

    /* Relaciones */
    /**
     * Retorna el objeto usuario del empleado correspondiente a la venta
     * @return Usuarios|null
     */
    public function getEmpleado(): ?Usuarios
    {
        if(!empty($this->empleado_id)){
            $this->empleado = Usuarios::searchForId($this->empleado_id) ?? new Usuarios();
            return $this->empleado;
        }
        return NULL;
    }

    /**
     * Retorna el objeto usuario del cliente correspondiente a la venta
     * @return Usuarios|null
     */
    public function getCliente(): ?Usuarios
    {
        if(!empty($this->cliente_id)){
            $this->cliente = Usuarios::searchForId($this->cliente_id) ?? new Usuarios();
            return $this->cliente;
        }
        return NULL;
    }

    /**
     * retorna un array de detalles venta que perteneces a una venta
     * @return array
     */
    public function getDetalleVenta(): ?array
    {

        $this->detalleVenta = DetalleCompras::search('SELECT * FROM weber.detalle_ventas where venta_id = '.$this->id);
        return $this->detalleVenta;
    }

    /**
     * @param string $query
     * @return bool|null
     */
    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':numero_serie' =>   $this->getNumeroSerie(),
            ':cliente_id' =>   $this->getClienteId(),
            ':empleado_id' =>   $this->getEmpleadoId(),
            ':fecha_venta' =>  $this->getFechaVenta()->toDateTimeString(), //YYYY-MM-DD HH:MM:SS
            ':monto' =>   $this->getMonto(),
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
    function insert(): ?bool
    {
        $query = "INSERT INTO weber.ventas VALUES (:id,:numero_serie,:cliente_id,:empleado_id,:fecha_venta,:monto,:estado,:created_at,:updated_at)";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    public function update() : ?bool
    {
        $query = "UPDATE weber.ventas SET 
            numero_serie = :numero_serie, cliente_id = :cliente_id,
            empleado_id = :empleado_id, fecha_venta = :fecha_venta,
            monto = :monto, estado = :estado,
            created_at = :created_at, updated_at = :updated_at WHERE id = :id";
        return $this->save($query);
    }

    /**
     * @return mixed
     */
    public function deleted() : bool
    {
        $this->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $this->update();                    //Guarda los cambios..
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function search($query) : ?array
    {
        try {
            $arrVentas = array();
            $tmp = new Ventas();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Venta = new Ventas($valor);
                array_push($arrVentas, $Venta);
                unset($Venta);
            }
            return $arrVentas;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    /**
     * @param $id
     * @return Ventas
     * @throws Exception
     */
    public static function searchForId($id) : ?Ventas
    {
        try {
            if ($id > 0) {
                $Venta = new Ventas();
                $Venta->Connect();
                $getrow = $Venta->getRow("SELECT * FROM weber.ventas WHERE id =?", array($id));
                $Venta->Disconnect();
                return ($getrow) ? new Ventas($getrow) : null;
            }else{
                throw new Exception('Id de venta Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function getAll() : array
    {
        return Ventas::search("SELECT * FROM weber.ventas");
    }

    /**
     * @param $numeroSerie
     * @return bool
     * @throws Exception
     */
    public static function facturaRegistrada($numeroSerie): bool
    {
        $numeroSerie = trim(strtolower($numeroSerie));
        $result = Compras::search("SELECT id FROM weber.ventas where numero_serie = '" . $numeroSerie. "'");
        if ( !empty($result) && count ($result) > 0 ) {
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
        return "Numero Serie: $this->numero_serie, Cliente: ".$this->getCliente()->nombresCompletos().", Empleado: ".$this->getEmpleado()->nombresCompletos().", Fecha Venta: $this->fecha_venta->toDateTimeString(), Monto: $this->monto, Estado: $this->estado";
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
            'numero_serie' => $this->getNumeroSerie(),
            'cliente' => $this->getCliente()->jsonSerialize(),
            'empleado' => $this->getEmpleado()->jsonSerialize(),
            'fecha_venta' => $this->getFechaVenta()->toDateTimeString(),
            'monto' => $this->getMonto(),
            'estado' => $this->getEstado(),
            'created_at' => $this->getCreatedAt()->toDateTimeString(),
            'updated_at' => $this->getUpdatedAt()->toDateTimeString(),
        ];
    }
}