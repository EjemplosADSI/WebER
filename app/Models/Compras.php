<?php

namespace App\Models;

use App\Interfaces\Model;
use Carbon\Carbon;
use Exception;
use JsonSerializable;

class Compras extends AbstractDBConnection implements Model
{
    private ?int $id;
    private string $numero_serie;
    private int $empleado_id;
    private int $proveedor_id;
    private Carbon $fecha_compra;
    private float $monto;
    private string $estado;
    private Carbon $created_at;
    private ?Carbon $updated_at;

    /* Relaciones */
    private ?Usuarios $empleado;
    private ?Usuarios $proveedor;
    private ?array $detalleCompra;


    /**
     * Venta constructor. Recibe un array asociativo
     * @param array $venta
     */
    public function __construct(array $venta = [])
    {
        parent::__construct();
        $this->setId($venta['id'] ?? NULL);
        $this->setNumeroSerie($venta['numero_serie'] ?? NULL);
        $this->setProveedorId($venta['proveedor_id'] ?? 0);
        $this->setEmpleadoId($venta['empleado_id'] ?? 0);
        $this->setFechaCompra(!empty($venta['fecha_compra']) ? Carbon::parse($venta['fecha_compra']) : new Carbon());
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
            $this->numero_serie = 'FC-'.($this->countRowsTable('ventas')+1).'-'.date('Y-m-d');
            $this->Disconnect();
        }else{
            $this->numero_serie = $numero_serie;
        }
    }

    /**
     * @return int
     */
    public function getProveedorId() : int
    {
        return $this->proveedor_id;
    }

    /**
     * @param int $proveedor_id
     */
    public function setProveedorId(int $proveedor_id): void
    {
        $this->proveedor_id = $proveedor_id;
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
    public function getFechaCompra() : Carbon
    {
        return $this->fecha_compra->locale('es');
    }

    /**
     * @param Carbon|mixed $fecha_compra
     */
    public function setFechaCompra(Carbon $fecha_compra): void
    {
        $this->fecha_compra = $fecha_compra;
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
            $arrDetallesCompra = $this->getDetalleCompra();
            if(!empty($arrDetallesCompra)){
                /* @var $arrDetallesCompra DetalleCompras[] */
                foreach ($arrDetallesCompra as $DetalleCompra){
                    $total += $DetalleCompra->getTotalProducto();
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
    public function getProveedor(): ?Usuarios
    {
        if(!empty($this->proveedor_id)){
            $this->proveedor = Usuarios::searchForId($this->proveedor_id) ?? new Usuarios();
            return $this->proveedor;
        }
        return NULL;
    }

    /**
     * retorna un array de detalles compra que perteneces a una venta
     * @return array
     */
    public function getDetalleCompra(): ?array
    {

        $this->detalleCompra = DetalleCompras::search('SELECT * FROM weber.detalle_compras where compra_id = '.$this->id);
        return $this->detalleCompra;
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
            ':proveedor_id' =>   $this->getProveedorId(),
            ':empleado_id' =>   $this->getEmpleadoId(),
            ':fecha_compra' =>  $this->getFechaCompra()->toDateTimeString(), //YYYY-MM-DD HH:MM:SS
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
        $query = "INSERT INTO weber.compras VALUES (:id,:numero_serie,:empleado_id,:proveedor_id,:fecha_compra,:monto,:estado,:created_at,:updated_at)";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    public function update() : ?bool
    {
        $query = "UPDATE weber.compras SET 
            numero_serie = :numero_serie, empleado_id = :empleado_id, 
            proveedor_id = :proveedor_id, fecha_compra = :fecha_compra,
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
            $arrCompras = array();
            $tmp = new Compras();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Compra = new Compras($valor);
                array_push($arrCompras, $Compra);
                unset($Compra);
            }
            return $arrCompras;
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
    public static function searchForId($id) : ?Compras
    {
        try {
            if ($id > 0) {
                $Compra = new Compras();
                $Compra->Connect();
                $getrow = $Compra->getRow("SELECT * FROM weber.compras WHERE id =?", array($id));
                $Compra->Disconnect();
                return ($getrow) ? new Compras($getrow) : null;
            }else{
                throw new Exception('Id de compra Invalido');
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
        return Compras::search("SELECT * FROM weber.compras");
    }

    /**
     * @param $numeroSerie
     * @return bool
     * @throws Exception
     */
    public static function facturaRegistrada($numeroSerie): bool
    {
        $numeroSerie = trim(strtolower($numeroSerie));
        $result = Compras::search("SELECT id FROM weber.compras where numero_serie = '" . $numeroSerie. "'");
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
        return "Numero Serie: $this->numero_serie, Cliente: ".$this->getProveedor()->nombresCompletos().", Empleado: ".$this->getEmpleado()->nombresCompletos().", Fecha Venta: $this->fecha_compra->toDateTimeString(), Monto: $this->monto, Estado: $this->estado";
    }


    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize() : array
    {
        return [
            'numero_serie' => $this->getNumeroSerie(),
            'proveedor' => $this->getProveedor()->jsonSerialize(),
            'empleado' => $this->getEmpleado()->jsonSerialize(),
            'fecha_compra' => $this->getFechaCompra()->toDateTimeString(),
            'monto' => $this->getMonto(),
            'estado' => $this->getEstado(),
            'created_at' => $this->getCreatedAt()->toDateTimeString(),
            'updated_at' => $this->getUpdatedAt()->toDateTimeString(),
        ];
    }
}