<?php


namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Fotos;
use Carbon\Carbon;

class FotosController
{
    private array $dataFotos;

    public function __construct(array $_FORM)
    {
        $this->dataFotos = array();
        $this->dataFotos['id'] = $_FORM['id'] ?? NULL;
        $this->dataFotos['nombre'] = $_FORM['nombre'] ?? '';
        $this->dataFotos['descripcion'] = $_FORM['descripcion'] ?? 0.0;
        $this->dataFotos['productos_id'] = $_FORM['productos_id'] ?? 0.0;
        $this->dataFotos['ruta'] = $_FORM['ruta'] ?? 0.0;
        $this->dataFotos['estado'] = $_FORM['estado'] ?? 'Activo';
    }

    public function create() {
        try {
            $Foto = new Fotos ($this->dataFotos);
            if ($Foto->insert()) {
                unset($_SESSION['frmFotos']);
                header("Location: ../../views/modules/fotos/index.php?respuesta=correcto");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit()
    {
        try {
            $foto = new Fotos($this->dataFotos);
            if($foto->update()){
                unset($_SESSION['frmFotos']);
            }

            header("Location: ../../views/modules/fotos/show.php?id=" . $foto->getId() . "&respuesta=correcto");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function searchForID (array $data){
        try {
            $result = Fotos::searchForId($data['id']);
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
            $result = Fotos::getAll();
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
            $ObjFoto = Fotos::searchForId($id);
            $ObjFoto->setEstado("Activo");
            if($ObjFoto->update()){
                header("Location: ../../views/modules/fotos/index.php");
            }else{
                header("Location: ../../views/modules/fotos/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function inactivate (int $id){
        try {
            $ObjFoto = Fotos::searchForId($id);
            $ObjFoto->setEstado("Inactivo");
            if($ObjFoto->update()){
                header("Location: ../../views/modules/fotos/index.php");
            }else{
                header("Location: ../../views/modules/fotos/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function selectFoto ($isMultiple=false,
                                           $isRequired=true,
                                           $id="idFoto",
                                           $nombre="idFoto",
                                           $defaultValue="",
                                           $class="",
                                           $where="",
                                           $arrExcluir = array()){
        $arrFoto = array();
        if($where != ""){
            $base = "SELECT * FROM fotos WHERE ";
            $arrFoto = Fotos::search($base.$where);
        }else{
            $arrFoto = Fotos::getAll();
        }

        $htmlSelect = "<select ".(($isMultiple) ? "multiple" : "")." ".(($isRequired) ? "required" : "")." id= '".$id."' name='".$nombre."' class='".$class."'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if(count($arrFoto) > 0){
            /* @var $arrFoto Fotos[] */
            foreach ($arrFoto as $foto)
                if (!FotosController::fotoIsInArray($foto->getId(),$arrExcluir))
                    $htmlSelect .= "<option ".(($foto != "") ? (($defaultValue == $foto->getId()) ? "selected" : "" ) : "")." value='".$foto->getId()."'>".$foto->getNombre()."</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    public static function fotoIsInArray($idFoto, $ArrFotos){
        if(count($ArrFotos) > 0){
            foreach ($ArrFotos as $Foto){
                if($Foto->getId() == $idFoto){
                    return true;
                }
            }
        }
        return false;
    }

}