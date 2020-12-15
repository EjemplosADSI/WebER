<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Compras;
use Carbon\Carbon;

class ComprasController{

    private array $dataCompra;

    public function __construct(array $_FORM)
    {
        $this->dataCompra = array();
        $this->dataCompra['id'] = $_FORM['id'] ?? NULL;
        $this->dataCompra['numero_serie'] = $_FORM['numero_serie'] ?? '';
        $this->dataCompra['empleado_id'] = $_FORM['empleado_id'] ?? 0;
        $this->dataCompra['proveedor_id'] = $_FORM['proveedor_id'] ?? 0;
        $this->dataCompra['fecha_compra'] = !empty($_FORM['fecha_compra']) ? Carbon::parse($_FORM['fecha_compra']) : new Carbon();
        $this->dataCompra['monto'] = $_FORM['monto'] ?? 0;
        $this->dataCompra['estado'] = $_FORM['estado'] ?? 'En progreso';
    }

    public function create() {
        try {
            $Compra = new Compras($this->dataCompra);
            if ($Compra->insert()) {
                unset($_SESSION['frmCompras']);
                $Compra->Connect();
                $id = $Compra->getLastId('compras');
                $Compra->Disconnect();
                header("Location: ../../views/modules/compras/create.php?id=" . $id . "");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            //header("Location: ../../views/modules/compras/create.php?respuesta=error");
        }
    }

    public function edit()
    {
        try {
            $Compra = new Compras($this->dataCompra);
            if($Compra->update()){
                unset($_SESSION['frmCompras']);
            }
            header("Location: ../../views/modules/compras/show.php?id=" . $Compra->getId() . "&respuesta=success&mensaje=Compra Actualizada");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            //header("Location: ../../views/modules/compras/edit.php?respuesta=error");
        }
    }

    static public function searchForID (array $data){
        try {
            $result = Compras::searchForId($data['id']);
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
            $result = Compras::getAll();
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
            $ObjVenta = Compras::searchForId($_GET['Id']);
            $ObjVenta->setEstado("Cancelada");
            if($ObjVenta->update()){
                header("Location: ../../views/modules/compras/index.php");
            }else{
                header("Location: ../../views/modules/compras/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            header("Location: ../../views/modules/compras/index.php?respuesta=error");
        }
    }

    static public function selectCompras (array $params = [] ){

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "venta_id";
        $params['name'] = $params['name'] ?? "venta_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrCompras = array();
        if($params['where'] != ""){
            $base = "SELECT * FROM compras WHERE ";
            $arrCompras = Compras::search($base.$params['where']);
        }else{
            $arrCompras = Compras::getAll();
        }

        $htmlSelect = "<select ".(($params['isMultiple']) ? "multiple" : "")." ".(($params['isRequired']) ? "required" : "")." id= '".$params['id']."' name='".$params['name']."' class='".$params['class']."'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if(count($arrCompras) > 0){
            /* @var $arrCompras Compras[] */
            foreach ($arrCompras as $compras)
                if (!ComprasController::CompraIsInArray($compras->getId(),$params['arrExcluir']))
                    $htmlSelect .= "<option ".(($compras != "") ? (($params['defaultValue'] == $compras->getId()) ? "selected" : "" ) : "")." value='".$compras->getId()."'>".$compras->getNumeroSerie()."</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    public static function CompraIsInArray($idCompra, $ArrCompra){
        if(count($ArrCompra) > 0){
            foreach ($ArrCompra as $Compra){
                if($Compra->getId() == $idCompra){
                    return true;
                }
            }
        }
        return false;
    }

}