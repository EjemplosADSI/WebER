<?php


namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Departamentos;


class DepartamentosController
{

    static public function searchForID(array $data)
    {
        try {
            $result = Departamentos::searchForId($data['id']);
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
            $result = Departamentos::getAll();
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

    static public function selectDepartamentos($isMultiple = false,
                                               $isRequired = true,
                                               $id = "departamento_id",
                                               $nombre = "departamento_id",
                                               $defaultValue = "",
                                               $class = "form-control",
                                               $where = "",
                                               $arrExcluir = array())
    {
        $arrDepartamentos = array();
        if ($where != "") {
            $base = "SELECT * FROM departamentos WHERE ";
            $arrDepartamentos = Departamentos::search($base . ' ' . $where);
        } else {
            $arrDepartamentos = Departamentos::getAll();
        }

        $htmlSelect = "<select " . (($isMultiple) ? "multiple" : "") . " " . (($isRequired) ? "required" : "") . " id= '" . $id . "' name='" . $nombre . "' class='" . $class . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (count($arrDepartamentos) > 0) {
            /* @var $arrDepartamentos Departamentos[] */
            foreach ($arrDepartamentos as $departamento)
                if (!DepartamentosController::departamentoIsInArray($departamento->getId(), $arrExcluir))
                    $htmlSelect .= "<option " . (($departamento != "") ? (($defaultValue == $departamento->getId()) ? "selected" : "") : "") . " value='" . $departamento->getId() . "'>" . $departamento->getNombre() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    private static function departamentoIsInArray($idDepartamento, $ArrDepartamentos)
    {
        if (count($ArrDepartamentos) > 0) {
            foreach ($ArrDepartamentos as $Departamento) {
                if ($Departamento->getId() == $idDepartamento) {
                    return true;
                }
            }
        }
        return false;
    }

}