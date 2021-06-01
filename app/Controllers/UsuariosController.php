<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Usuarios;
use Carbon\Carbon;

class UsuariosController
{
    private array $dataUsuario;

    public function __construct(array $_FORM)
    {
        $this->dataUsuario = array();
        $this->dataUsuario['id'] = $_FORM['id'] ?? NULL;
        $this->dataUsuario['nombres'] = $_FORM['nombres'] ?? NULL;
        $this->dataUsuario['apellidos'] = $_FORM['apellidos'] ?? null;
        $this->dataUsuario['tipo_documento'] = $_FORM['tipo_documento'] ?? NULL;
        $this->dataUsuario['documento'] = $_FORM['documento'] ?? NULL;
        $this->dataUsuario['telefono'] = $_FORM['telefono'] ?? NULL;
        $this->dataUsuario['direccion'] = $_FORM['direccion'] ?? NULL;
        $this->dataUsuario['municipio_id'] = $_FORM['municipio_id'] ?? NULL;
        $this->dataUsuario['fecha_nacimiento'] = !empty($_FORM['fecha_nacimiento']) ? Carbon::parse($_FORM['fecha_nacimiento']) : new Carbon();
        $this->dataUsuario['user'] = $_FORM['user'] ?? NULL;
        $this->dataUsuario['password'] = $_FORM['password'] ?? NULL;
        $this->dataUsuario['rol'] = $_FORM['rol'] ?? 'Cliente';
        $this->dataUsuario['foto'] = $_FORM['nameFoto'] ?? NULL;
        $this->dataUsuario['estado'] = $_FORM['estado'] ?? 'Activo';
    }

    public function create($withFiles = null) {
        try {
            if (!empty($this->dataUsuario['documento']) && !Usuarios::usuarioRegistrado($this->dataUsuario['documento'])) {
                if(!empty($withFiles)){
                    $fotoUsuario = $withFiles['foto'];
                    $resultUpload = GeneralFunctions::subirArchivo($fotoUsuario, "views/public/uploadFiles/photos/");
                    $this->dataUsuario['foto'] = ($resultUpload != false) ? $resultUpload : NULL;
                }
                $Usuario = new Usuarios ($this->dataUsuario);
                if ($Usuario->insert()) {
                    unset($_SESSION['frmUsuarios']);
                    header("Location: ../../views/modules/usuarios/index.php?respuesta=success&mensaje=Usuario Registrado");
                }
            } else {
                header("Location: ../../views/modules/usuarios/create.php?respuesta=error&mensaje=Usuario ya registrado");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit($withFiles = null)
    {
        try {
            if(!empty($withFiles)){
                $fotoUsuario = $withFiles['foto'];
                if($fotoUsuario['error'] == 0){ //Si la foto se selecciono correctamente
                    $resultUpload = GeneralFunctions::subirArchivo($fotoUsuario, "views/public/uploadFiles/photos/");
                    if($resultUpload != false){
                        GeneralFunctions::eliminarArchivo("views/public/uploadFiles/photos/".$this->dataUsuario['foto']);
                        $this->dataUsuario['foto'] = $resultUpload;
                    }
                }
            }

            $user = new Usuarios($this->dataUsuario);
            if($user->update()){
                unset($_SESSION['frmUsuarios']);
            }

            header("Location: ../../views/modules/usuarios/show.php?id=" . $user->getId() . "&respuesta=success&mensaje=Usuario Actualizado");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function searchForID(array $data)
    {
        try {
            $result = Usuarios::searchForId($data['id']);
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

    static public function getAll(array $data = null)
    {
        try {
            $result = Usuarios::getAll();
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

    static public function activate(int $id)
    {
        try {
            $ObjUsuario = Usuarios::searchForId($id);
            $ObjUsuario->setEstado("Activo");
            if ($ObjUsuario->update()) {
                header("Location: ../../views/modules/usuarios/index.php");
            } else {
                header("Location: ../../views/modules/usuarios/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function inactivate(int $id)
    {
        try {
            $ObjUsuario = Usuarios::searchForId($id);
            $ObjUsuario->setEstado("Inactivo");
            if ($ObjUsuario->update()) {
                header("Location: ../../views/modules/usuarios/index.php");
            } else {
                header("Location: ../../views/modules/usuarios/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function selectUsuario(array $params = []) {

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "usuario_id";
        $params['name'] = $params['name'] ?? "usuario_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrUsuarios = array();
        if ($params['where'] != "") {
            $base = "SELECT * FROM usuarios WHERE ";
            $arrUsuarios = Usuarios::search($base . ' ' . $params['where']);
        } else {
            $arrUsuarios = Usuarios::getAll();
        }
        $htmlSelect = "<select " . (($params['isMultiple']) ? "multiple" : "") . " " . (($params['isRequired']) ? "required" : "") . " id= '" . $params['id'] . "' name='" . $params['name'] . "' class='" . $params['class'] . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (is_array($arrUsuarios) && count($arrUsuarios) > 0) {
            /* @var $arrUsuarios Usuarios[] */
            foreach ($arrUsuarios as $usuario)
                if (!UsuariosController::usuarioIsInArray($usuario->getId(), $params['arrExcluir']))
                    $htmlSelect .= "<option " . (($usuario != "") ? (($params['defaultValue'] == $usuario->getId()) ? "selected" : "") : "") . " value='" . $usuario->getId() . "'>" . $usuario->getDocumento() . " - " . $usuario->getNombres() . " " . $usuario->getApellidos() . "</option>";
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
                $respuesta = $tmpUser->login($_POST['user'], $_POST['password']);
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

}