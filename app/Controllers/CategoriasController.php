<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Categorias;
use Carbon\Carbon;

class CategoriasController{

    private array $dataCategoria;

    public function __construct(array $_FORM)
    {
        $this->dataCategoria = array();
        $this->dataCategoria['id'] = $_FORM['id'] ?? NULL;
        $this->dataCategoria['nombre'] = $_FORM['nombre'] ?? '';
        $this->dataCategoria['descripcion'] = $_FORM['descripcion'] ?? '';
        $this->dataCategoria['estado'] = $_FORM['estado'] ?? 'Activo';
    }

    public function create() {
        try {
            if (!empty($this->dataCategoria['nombre']) && !Categorias::categoriaRegistrada($this->dataCategoria['nombre'])) {
                $Categoria = new Categorias($this->dataCategoria);
                if ($Categoria->insert()) {
                    unset($_SESSION['frmCategorias']);
                    header("Location: ../../views/modules/categorias/index.php?respuesta=correcto");
                }
            } else {
                header("Location: ../../views/modules/categorias/create.php?respuesta=error&mensaje=Categoria ya registrado");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit()
    {
        try {
            $Categoria = new Categorias($this->dataCategoria);
            if($Categoria->update()){
                unset($_SESSION['frmCategorias']);
            }

            header("Location: ../../views/modules/categorias/show.php?id=" . $Categoria->getId() . "&respuesta=correcto");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function searchForID (array $data){
        try {
            $result = Categorias::searchForId($data['id']);
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
            $result = Categorias::getAll();
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
            $ObjCategoria = Categorias::searchForId($id);
            $ObjCategoria->setEstado("Activo");
            if($ObjCategoria->update()){
                header("Location: ../../views/modules/categorias/index.php");
            }else{
                header("Location: ../../views/modules/categorias/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function inactivate (int $id){
        try {
            $ObjCategoria = Categorias::searchForId($id);
            $ObjCategoria->setEstado("Inactivo");
            if($ObjCategoria->update()){
                header("Location: ../../views/modules/categorias/index.php");
            }else{
                header("Location: ../../views/modules/categorias/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function selectCategoria ($isMultiple=false,
                                            $isRequired=true,
                                            $id="categoria_id",
                                            $nombre="categoria_id",
                                            $defaultValue="",
                                            $class="",
                                            $where="",
                                            $arrExcluir = array()){
        $arrCategoria = array();
        if($where != ""){
            $base = "SELECT * FROM categorias WHERE ";
            $arrCategoria = Categorias::search($base.$where);
        }else{
            $arrCategoria = Categorias::getAll();
        }

        $htmlSelect = "<select ".(($isMultiple) ? "multiple" : "")." ".(($isRequired) ? "required" : "")." id= '".$id."' name='".$nombre."' class='".$class."'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if(count($arrCategoria) > 0){
            /* @var $arrCategoria Categorias[] */
            foreach ($arrCategoria as $categoria)
                if (!CategoriasController::categoriaIsInArray($categoria->getId(),$arrExcluir))
                    $htmlSelect .= "<option ".(($categoria != "") ? (($defaultValue == $categoria->getId()) ? "selected" : "" ) : "")." value='".$categoria->getId()."'>".$categoria->getNombre()."</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    public static function categoriaIsInArray($idCategoria, $ArrCategoria){
        if(count($ArrCategoria) > 0){
            foreach ($ArrCategoria as $Categoria){
                if($Categoria->getId() == $idCategoria){
                    return true;
                }
            }
        }
        return false;
    }

}