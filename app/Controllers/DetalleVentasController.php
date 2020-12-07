<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\DetalleVentas;
use Carbon\Carbon;

class DetalleVentasController
{
    private array $dataDetalleVenta;

    public function __construct(array $_FORM)
    {
        $this->dataDetalleVenta = array();
        $this->dataDetalleVenta['id'] = $_FORM['id'] ?? NULL;
        $this->dataDetalleVenta['venta_id'] = $_FORM['venta_id'] ?? '';
        $this->dataDetalleVenta['producto_id'] = $_FORM['producto_id'] ?? '';
        $this->dataDetalleVenta['cantidad'] = $_FORM['cantidad'] ?? '';
        $this->dataDetalleVenta['precio_venta'] = $_FORM['precio_venta'] ?? '';
    }

    public function create()
    {
        try {
            if (!empty($this->dataDetalleVenta['venta_id']) and !empty($this->dataDetalleVenta['producto_id'])) {
                if(DetalleVentas::productoEnFactura($this->dataDetalleVenta['venta_id'], $this->dataDetalleVenta['producto_id'])){
                    $this->edit();
                }else{
                    $DetalleVenta = new DetalleVentas($this->dataDetalleVenta);
                    if ($DetalleVenta->insert()) {
                        unset($_SESSION['frmDetalleVentas']);
                        header("Location: ../../views/modules/ventas/create.php?id=".$this->dataDetalleVenta['venta_id']."&respuesta=success&mensaje=Producto Agregado");
                    }
                }
            } else {
                header("Location: ../../views/modules/ventas/create.php?id=".$this->dataDetalleVenta['venta_id']."&respuesta=error&mensaje=Faltan parametros");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit()
    {
        try {
            $arrDetalleVenta = DetalleVentas::search("SELECT * FROM detalle_ventas WHERE venta_id = ".$this->dataDetalleVenta['venta_id']." and producto_id = ".$this->dataDetalleVenta['producto_id']);
            /* @var $arrDetalleVenta DetalleVentas[] */
            $DetalleVenta = $arrDetalleVenta[0];
            $OldCantidad = $DetalleVenta->getCantidad();
            $DetalleVenta->setCantidad($OldCantidad + $this->dataDetalleVenta['cantidad']);
            if ($DetalleVenta->update()) {
                $DetalleVenta->getProducto()->substractStock($this->dataDetalleVenta['cantidad']);
                unset($_SESSION['frmDetalleVentas']);
                header("Location: ../../views/modules/ventas/create.php?id=".$this->dataDetalleVenta['venta_id']."&respuesta=success&mensaje=Producto Actualizado");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function deleted (int $id){
        try {
            $ObjDetalleVenta = DetalleVentas::searchForId($id);
            $objProducto = $ObjDetalleVenta->getProducto();
            if($ObjDetalleVenta->deleted()){
                $objProducto->addStock($ObjDetalleVenta->getCantidad());
                header("Location: ../../views/modules/ventas/create.php?id=".$ObjDetalleVenta->getVentasId()."&respuesta=success&mensaje=Producto Eliminado");
            }else{
                header("Location: ../../views/modules/ventas/create.php?id=".$ObjDetalleVenta->getVentasId()."&respuesta=error&mensaje=Error al eliminar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function searchForID(array $data)
    {
        try {
            $result = DetalleVentas::searchForId($data['id']);
            if (!empty($data['request']) and $data['request'] === 'ajax' and !empty($result)) {
                header('Content-type: application/json; charset=utf-8');
                $result = json_encode($result->jsonSerialize());
            }
            return $result;
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    static public function getAll()
    {
        try {
            $result = DetalleVentas::getAll();
            if (!empty($data['request']) and $data['request'] === 'ajax') {
                header('Content-type: application/json; charset=utf-8');
                $result = json_encode($result);
            }
            return $result;
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }
}