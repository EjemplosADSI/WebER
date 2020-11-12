<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Productos;
use Carbon\Carbon;

class ProductosController{

    private array $dataProducto;

    public function __construct(array $_FORM)
    {
        $this->dataProducto = array();
        $this->dataProducto['id'] = $_FORM['id'] ?? NULL;
        $this->dataProducto['nombre'] = $_FORM['nombre'] ?? '';
        $this->dataProducto['precio'] = $_FORM['precio'] ?? 0.0;
        $this->dataProducto['porcentaje_ganancia'] = $_FORM['porcentaje_ganancia'] ?? 0.0;
        $this->dataProducto['stock'] = $_FORM['stock'] ?? 0.0;
        $this->dataProducto['estado'] = $_FORM['estado'] ?? 'Activo';
    }

    public function create() {
        try {
            if (!empty($this->dataProducto['nombre']) && !Productos::productoRegistrado($this->dataProducto['nombre'])) {
                $Producto = new Productos ($this->dataProducto);
                if ($Producto->insert()) {
                    unset($_SESSION['frmProductos']);
                    header("Location: ../../views/modules/productos/index.php?respuesta=correcto");
                }
            } else {
                header("Location: ../../views/modules/productos/create.php?respuesta=error&mensaje=Producto ya registrado");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit()
    {
        try {
            $producto = new Productos($this->dataProducto);
            if($producto->update()){
                unset($_SESSION['frmProductos']);
            }

            header("Location: ../../views/modules/productos/show.php?id=" . $producto->getId() . "&respuesta=correcto");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function searchForID (array $data){
        try {
            $result = Productos::searchForId($data['id']);
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
            $result = Productos::getAll();
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

    static public function activate (int $id){
        try {
            $ObjProducto = Productos::searchForId($id);
            $ObjProducto->setEstado("Activo");
            if($ObjProducto->update()){
                header("Location: ../../views/modules/productos/index.php");
            }else{
                header("Location: ../../views/modules/productos/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function inactivate (int $id){
        try {
            $ObjProducto = Productos::searchForId($id);
            $ObjProducto->setEstado("Inactivo");
            if($ObjProducto->update()){
                header("Location: ../../views/modules/productos/index.php");
            }else{
                header("Location: ../../views/modules/productos/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function selectProducto ($isMultiple=false,
                                           $isRequired=true,
                                           $id="idProducto",
                                           $nombre="idProducto",
                                           $defaultValue="",
                                           $class="",
                                           $where="",
                                           $arrExcluir = array()){
        $arrProducto = array();
        if($where != ""){
            $base = "SELECT * FROM productos WHERE ";
            $arrProducto = Productos::search($base.$where);
        }else{
            $arrProducto = Productos::getAll();
        }

        $htmlSelect = "<select ".(($isMultiple) ? "multiple" : "")." ".(($isRequired) ? "required" : "")." id= '".$id."' name='".$nombre."' class='".$class."'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if(count($arrProducto) > 0){
            /* @var $arrProducto Productos[] */
            foreach ($arrProducto as $producto)
                if (!ProductosController::productoIsInArray($producto->getId(),$arrExcluir))
                    $htmlSelect .= "<option ".(($producto != "") ? (($defaultValue == $producto->getId()) ? "selected" : "" ) : "")." value='".$producto->getId()."'>".$producto->getNombre()."</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    public static function productoIsInArray($idProducto, $ArrProducto){
        if(count($ArrProducto) > 0){
            foreach ($ArrProducto as $Producto){
                if($Producto->getId() == $idProducto){
                    return true;
                }
            }
        }
        return false;
    }

}