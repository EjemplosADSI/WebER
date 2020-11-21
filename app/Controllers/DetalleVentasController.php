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
            if (!empty($this->dataDetalleVenta['venta_id']) and !empty($this->dataDetalleVenta['producto_id']) and !DetalleVentas::productoEnFactura($this->dataDetalleVenta['venta_id'], $this->dataDetalleVenta['producto_id'])) {
                $DetalleVenta = new DetalleVentas($this->dataDetalleVenta);
                if ($DetalleVenta->insert()) {
                    unset($_SESSION['frmDetalleVentas']);
                    header("Location: ../../views/modules/ventas/create.php?respuesta=correcto");
                }
            } else {
                header("Location: ../../views/modules/ventas/create.php?respuesta=error&mensaje=Producto ya agregado a la compra");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit()
    {
        try {
            $DetalleVenta = new DetalleVentas($this->dataDetalleVenta);
            if($DetalleVenta->update()){
                unset($_SESSION['frmDetalleVentas']);
            }
            header("Location: ../../views/modules/ventas/create.php?respuesta=correcto&mensaje=Producto Actualizado");
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