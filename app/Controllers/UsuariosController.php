<?php

namespace App\Controllers;

if(session_status() == PHP_SESSION_NONE){ //Si la session no ha iniciado
    session_start();
}

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Usuarios;
use Carbon\Carbon;

if (!empty($_GET['action'])) {
    UsuariosController::main($_GET['action']);
}

class UsuariosController
{

    static function main($action)
    {
        if ($action == "create") {
            UsuariosController::create();
        } else if ($action == "edit") {
            UsuariosController::edit();
        } else if ($action == "searchForID") {
            UsuariosController::searchForID($_REQUEST['idPersona']);
        } else if ($action == "searchAll") {
            UsuariosController::getAll();
        } else if ($action == "activate") {
            UsuariosController::activate();
        } else if ($action == "inactivate") {
            UsuariosController::inactivate();
        }else if ($action == "login"){
            UsuariosController::login();
        }else if($action == "cerrarSession"){
            UsuariosController::cerrarSession();
        }

    }

    static public function create()
    {
        try {
            $arrayUsuario = array();
            $arrayUsuario['nombres'] = $_POST['nombres'];
            $arrayUsuario['apellidos'] = $_POST['apellidos'];
            $arrayUsuario['tipo_documento'] = $_POST['tipo_documento'];
            $arrayUsuario['documento'] = $_POST['documento'];
            $arrayUsuario['telefono'] = $_POST['telefono'];
            $arrayUsuario['direccion'] = $_POST['direccion'];
            $arrayUsuario['fecha_nacimiento'] = Carbon::parse($_POST['fecha_nacimiento']);
            $arrayUsuario['user'] = $_POST['user'] ?? null;
            $arrayUsuario['password'] = $_POST['password'] ?? null;
            $arrayUsuario['rol'] = 'Cliente';
            $arrayUsuario['estado'] = 'Activo';
            $arrayUsuario['fecha_registro'] = Carbon::now(); //Fecha Actual
            if (!Usuarios::usuarioRegistrado($arrayUsuario['documento'])) {
                $Usuario = new Usuarios ($arrayUsuario);
                if ($Usuario->insert()) {
                    header("Location: ../../views/modules/usuarios/index.php?respuesta=correcto");
                }
            } else {
                header("Location: ../../views/modules/usuarios/create.php?respuesta=error&mensaje=Usuario ya registrado");
            }
        } catch (Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/usuarios/create.php?respuesta=error&mensaje=" . $e->getMessage());
        }
    }

    static public function edit()
    {
        try {
            $arrayUsuario = array();
            $arrayUsuario['nombres'] = $_POST['nombres'];
            $arrayUsuario['apellidos'] = $_POST['apellidos'];
            $arrayUsuario['tipo_documento'] = $_POST['tipo_documento'];
            $arrayUsuario['documento'] = $_POST['documento'];
            $arrayUsuario['telefono'] = $_POST['telefono'];
            $arrayUsuario['fecha_nacimiento'] = Carbon::parse($_POST['fecha_nacimiento']);
            $arrayUsuario['direccion'] = $_POST['direccion'];
            $arrayUsuario['user'] = $_POST['user'];
            $arrayUsuario['password'] = $_POST['password'];
            $arrayUsuario['rol'] = $_POST['rol'];
            $arrayUsuario['estado'] = $_POST['estado'];
            $arrayUsuario['id'] = $_POST['id'];
            $arrayUsuario['fecha_registro'] = Carbon::now(); //Fecha Actual

            $user = (new Usuarios($arrayUsuario));
            $user->update();

            header("Location: ../../views/modules/usuarios/show.php?id=" . $user->getId() . "&respuesta=correcto");
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/usuarios/edit.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }

    static public function searchForID($id)
    {
        try {
            return Usuarios::searchForId($id);
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/usuarios/manager.php?respuesta=error");
        }
    }

    /**
     * Retorna la lectura de un archivo en formato csv
     *
     * @param string $fileName
     * @param string $delimiter
     * @param string $path
     * @return array
     * @throws Exception
     */
    static public function getAll()
    {
        try {
            return Usuarios::getAll();
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'log', 'errorStack');
            //header("Location: ../Vista/modules/persona/manager.php?respuesta=error");
        }
    }

    static public function activate()
    {
        try {
            $ObjUsuario = Usuarios::searchForId($_GET['Id']);
            $ObjUsuario->setEstado("Activo");
            if ($ObjUsuario->update()) {
                header("Location: ../../views/modules/usuarios/index.php");
            } else {
                header("Location: ../../views/modules/usuarios/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/usuarios/index.php?respuesta=error&mensaje=".$e->getMessage());
        }
    }

    static public function inactivate()
    {
        try {
            $ObjUsuario = Usuarios::searchForId($_GET['Id']);
            $ObjUsuario->setEstado("Inactivo");
            if ($ObjUsuario->update()) {
                header("Location: ../../views/modules/usuarios/index.php");
            } else {
                header("Location: ../../views/modules/usuarios/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
            //header("Location: ../../views/modules/usuarios/index.php?respuesta=error");
        }
    }

    static public function selectUsuario($isMultiple = false,
                                         $isRequired = true,
                                         $id = "idUsuario",
                                         $nombre = "idUsuario",
                                         $defaultValue = "",
                                         $class = "form-control",
                                         $where = "",
                                         $arrExcluir = array())
    {
        $arrUsuarios = array();
        if ($where != "") {
            $base = "SELECT * FROM usuarios WHERE ";
            $arrUsuarios = Usuarios::search($base . ' ' . $where);
        } else {
            $arrUsuarios = Usuarios::getAll();
        }

        $htmlSelect = "<select " . (($isMultiple) ? "multiple" : "") . " " . (($isRequired) ? "required" : "") . " id= '" . $id . "' name='" . $nombre . "' class='" . $class . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (count($arrUsuarios) > 0) {
            /* @var $arrUsuarios \App\Models\Usuarios[] */
            foreach ($arrUsuarios as $usuario)
                if (!UsuariosController::usuarioIsInArray($usuario->getId(), $arrExcluir))
                    $htmlSelect .= "<option " . (($usuario != "") ? (($defaultValue == $usuario->getId()) ? "selected" : "") : "") . " value='" . $usuario->getId() . "'>" . $usuario->getDocumento() . " - " . $usuario->getNombres() . " " . $usuario->getApellidos() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    private static function usuarioIsInArray($idUsuario, $ArrUsuarios)
    {
        if (count($ArrUsuarios) > 0) {
            foreach ($ArrUsuarios as $Usuario) {
                if ($Usuario->getId() == $idUsuario) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function login (){
        try {
            if(!empty($_POST['user']) && !empty($_POST['password'])){
                $tmpUser = new Usuarios();
                $respuesta = $tmpUser->Login($_POST['user'], $_POST['password']);
                if (is_a($respuesta,"App\Models\Usuarios")) {
                    $_SESSION['UserInSession'] = $respuesta->jsonSerialize();
                    header("Location: ../../views/index.php");
                }else{
                    header("Location: ../../views/modules/site/login.php?respuesta=error&mensaje=".$respuesta);
                }
            }else{
                header("Location: ../../views/modules/site/login.php?respuesta=error&mensaje=Datos Vacíos");
            }
        } catch (\Exception $e) {
            header("Location: ../../views/modules/site/login.php?respuesta=error".$e->getMessage());
        }
    }

    public static function cerrarSession (){
        session_unset();
        session_destroy();
        header("Location: ../../views/modules/site/login.php");
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