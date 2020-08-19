<?php

namespace App\Controllers;
require_once(__DIR__.'/../Models/Productos.php');
require_once(__DIR__.'/../Models/GeneralFunctions.php');

use App\Models\GeneralFunctions;
use App\Models\Productos;

if(!empty($_GET['action'])){
    ProductosController::main($_GET['action']);
}

class ProductosController{

    static function main($action)
    {
        if ($action == "create") {
            ProductosController::create();
        } else if ($action == "edit") {
            ProductosController::edit();
        } else if ($action == "searchForID") {
            ProductosController::searchForID($_REQUEST['idProducto']);
        } else if ($action == "searchForIDAjax") {
            ProductosController::searchForID($_REQUEST['idProducto'], 'Ajax');
        } else if ($action == "searchAll") {
            ProductosController::getAll();
        } else if ($action == "activate") {
            ProductosController::activate();
        } else if ($action == "inactivate") {
            ProductosController::inactivate();
        }/*else if ($action == "login"){
            UsuariosController::login();
        }else if($action == "cerrarSession"){
            UsuariosController::cerrarSession();
        }*/
    }

    static public function create()
    {
        try {
            $arrayProducto = array();
            $arrayProducto['nombres'] = $_POST['nombres'];
            $arrayProducto['precio'] = $_POST['precio'];
            $arrayProducto['porcentaje_ganancia'] = $_POST['porcentaje_ganancia'];
            $arrayProducto['stock'] = $_POST['stock'];
            $arrayProducto['estado'] = 'Activo';
            if(!Productos::productoRegistrado($arrayProducto['nombres'])){
                $Producto = new Productos($arrayProducto);
                if($Producto->create()){
                    header("Location: ../../views/modules/productos/index.php?respuesta=correcto");
                }
            }else{
                header("Location: ../../views/modules/productos/create.php?respuesta=error&mensaje=Producto ya registrado");
            }
        } catch (Exception $e) {
            GeneralFunctions::console( $e, 'error', 'errorStack');
            header("Location: ../../views/modules/productos/create.php?respuesta=error&mensaje=" . $e->getMessage());
        }
    }

    static public function edit (){
        try {
            $arrayProducto = array();
            $arrayProducto['nombres'] = $_POST['nombres'];
            $arrayProducto['precio'] = $_POST['precio'];
            $arrayProducto['porcentaje_ganancia'] = $_POST['porcentaje_ganancia'];
            $arrayProducto['stock'] = $_POST['stock'];
            $arrayProducto['estado'] = $_POST['estado'];
            $arrayProducto['id'] = $_POST['id'];

            $Producto = new Productos($arrayProducto);
            $Producto->update();

            header("Location: ../../views/modules/productos/show.php?id=".$Producto->getId()."&respuesta=correcto");
        } catch (\Exception $e) {
            GeneralFunctions::console( $e, 'error', 'errorStack');
            header("Location: ../../views/modules/productos/edit.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }

    static public function activate (){
        try {
            $ObjProducto = Productos::searchForId($_GET['Id']);
            $ObjProducto->setEstado("Activo");
            if($ObjProducto->update()){
                header("Location: ../../views/modules/productos/index.php");
            }else{
                header("Location: ../../views/modules/productos/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::console( $e, 'error', 'errorStack');
            header("Location: ../../views/modules/productos/index.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }

    static public function inactivate (){
        try {
            $ObjProducto = Productos::searchForId($_GET['Id']);
            $ObjProducto->setEstado("Inactivo");
            if($ObjProducto->update()){
                header("Location: ../../views/modules/productos/index.php");
            }else{
                header("Location: ../../views/modules/productos/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::console( $e, 'error', 'errorStack');
            header("Location: ../../views/modules/productos/index.php?respuesta=error");
        }
    }

    static public function searchForID ($id, $method = 'normal'){
        try {
            $result = Productos::searchForId($id);
            if ($method === 'normal') {
                return $result;
            }else{
                header('Content-type: application/json; charset=utf-8');
                echo json_encode($result->jsonSerialize());
            }
        } catch (\Exception $e) {
            GeneralFunctions::console( $e, 'error', 'errorStack');
            if ($method === 'normal'){
                header("Location: ../../views/modules/productos/manager.php?respuesta=error");
            }else{
                header('Content-type: application/json; charset=utf-8');
                echo json_encode($e);
            }
        }
    }

    static public function getAll (){
        try {
            return Productos::getAll();
        } catch (\Exception $e) {
            GeneralFunctions::console( $e, 'log', 'errorStack');
            header("Location: ../Vista/modules/persona/manager.php?respuesta=error");
        }
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
            /* @var $arrProducto \App\Models\Productos[] */
            foreach ($arrProducto as $producto)
                if (!ProductosController::productoIsInArray($producto->getId(),$arrExcluir))
                    $htmlSelect .= "<option ".(($producto != "") ? (($defaultValue == $producto->getId()) ? "selected" : "" ) : "")." value='".$producto->getId()."'>".$producto->getNombres()."</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    /*
    public function buscar ($Query){
        try {
            return Persona::buscar($Query);
        } catch (Exception $e) {
            header("Location: ../Vista/modules/persona/manager.php?respuesta=error");
        }
    }

    static public function asociarEspecialidad (){
        try {
            $Persona = new Persona();
            $Persona->asociarEspecialidad($_POST['Persona'],$_POST['Especialidad']);
            header("Location: ../Vista/modules/persona/managerSpeciality.php?respuesta=correcto&id=".$_POST['Persona']);
        } catch (Exception $e) {
            header("Location: ../Vista/modules/persona/managerSpeciality.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }

    static public function eliminarEspecialidad (){
        try {
            $ObjPersona = new Persona();
            if(!empty($_GET['Persona']) && !empty($_GET['Especialidad'])){
                $ObjPersona->eliminarEspecialidad($_GET['Persona'],$_GET['Especialidad']);
            }else{
                throw new Exception('No se recibio la informacion necesaria.');
            }
            header("Location: ../Vista/modules/persona/managerSpeciality.php?id=".$_GET['Persona']);
        } catch (Exception $e) {
            var_dump($e);
            //header("Location: ../Vista/modules/persona/manager.php?respuesta=error");
        }
    }*/

}