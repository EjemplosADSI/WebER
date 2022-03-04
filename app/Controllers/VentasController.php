<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Ventas;
use Carbon\Carbon;

class VentasController{

    private array $dataVenta;

    public function __construct(array $_FORM)
    {
        $this->dataVenta = array();
        $this->dataVenta['id'] = $_FORM['id'] ?? NULL;
        $this->dataVenta['numero_serie'] = $_FORM['numero_serie'] ?? '';
        $this->dataVenta['cliente_id'] = $_FORM['cliente_id'] ?? 0;
        $this->dataVenta['empleado_id'] = $_FORM['empleado_id'] ?? 0;
        $this->dataVenta['fecha_venta'] = !empty($_FORM['fecha_venta']) ? Carbon::parse($_FORM['fecha_venta']) : new Carbon();
        $this->dataVenta['monto'] = $_FORM['monto'] ?? 0;
        $this->dataVenta['estado'] = $_FORM['estado'] ?? 'En progreso';
    }

    public function create() {
        try {
            $Venta = new Ventas($this->dataVenta);
            if ($Venta->insert()) {
                unset($_SESSION['frmVentas']);
                $Venta->Connect();
                $id = $Venta->getLastId('ventas');
                $Venta->Disconnect();
                header("Location: ../../views/modules/ventas/create.php?id=" . $id . "");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            //header("Location: ../../views/modules/ventas/create.php?respuesta=error");
        }
    }

    public function edit()
    {
        try {
            $Venta = new Ventas($this->dataVenta);
            if($Venta->update()){
                unset($_SESSION['frmVentas']);
            }
            header("Location: ../../views/modules/ventas/show.php?id=" . $Venta->getId() . "&respuesta=success&mensaje=Venta Actualizada");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            //header("Location: ../../views/modules/ventas/edit.php?respuesta=error");
        }
    }

    static public function searchForID (array $data){
        try {
            $result = Ventas::searchForId($data['id']);
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

    static public function getAll (array $data = null){
        try {
            $result = Ventas::getAll();
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

    static public function cancel(){
        try {
            $ObjVenta = Ventas::searchForId($_GET['Id']);
            $ObjVenta->setEstado("Cancelada");
            if($ObjVenta->update()){
                $ObjVenta->cancelFactura();
                header("Location: ../../views/modules/ventas/index.php");
            }else{
                header("Location: ../../views/modules/ventas/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            header("Location: ../../views/modules/ventas/index.php?respuesta=error");
        }
    }

    static public function selectVentas (array $params = [] ){

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "venta_id";
        $params['name'] = $params['name'] ?? "venta_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrVentas = array();
        if($params['where'] != ""){
            $base = "SELECT * FROM ventas WHERE ";
            $arrVentas = Ventas::search($base.$params['where']);
        }else{
            $arrVentas = Ventas::getAll();
        }

        $htmlSelect = "<select ".(($params['isMultiple']) ? "multiple" : "")." ".(($params['isRequired']) ? "required" : "")." id= '".$params['id']."' name='".$params['name']."' class='".$params['class']."'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if(is_array($arrVentas) && count($arrVentas) > 0){
            /* @var $arrVentas Ventas[] */
            foreach ($arrVentas as $ventas)
                if (!VentasController::ventaIsInArray($ventas->getId(),$params['arrExcluir']))
                    $htmlSelect .= "<option ".(($ventas != "") ? (($params['defaultValue'] == $ventas->getId()) ? "selected" : "" ) : "")." value='".$ventas->getId()."'>".$ventas->getNumeroSerie()."</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    public static function ventaIsInArray($idVenta, $ArrVentas){
        if(count($ArrVentas) > 0){
            foreach ($ArrVentas as $Venta){
                if($Venta->getId() == $idVenta){
                    return true;
                }
            }
        }
        return false;
    }

}