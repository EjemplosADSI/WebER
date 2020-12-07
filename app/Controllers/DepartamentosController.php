<?php


namespace App\Controllers;


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

    static public function selectDepartamentos (array $params = [])
    {
        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "municipio_id";
        $params['name'] = $params['name'] ?? "municipio_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrDepartamentos = array();
        if ($params['where'] != "") {
            $base = "SELECT * FROM departamentos WHERE ";
            $arrDepartamentos = Departamentos::search($base . ' ' . $params['where']);
        } else {
            $arrDepartamentos = Departamentos::getAll();
        }

        $htmlSelect = "<select " . (($params['isMultiple']) ? "multiple" : "") . " " . (($params['isRequired']) ? "required" : "") . " id= '" . $params['id'] . "' name='" . $params['name'] . "' class='" . $params['class'] . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (count($arrDepartamentos) > 0) {
            /* @var $arrDepartamentos Departamentos[] */
            foreach ($arrDepartamentos as $departamento)
                if (!DepartamentosController::departamentoIsInArray($departamento->getId(), $params['arrExcluir']))
                    $htmlSelect .= "<option " . (($departamento != "") ? (($params['defaultValue'] == $departamento->getId()) ? "selected" : "") : "") . " value='" . $departamento->getId() . "'>" . $departamento->getNombre() . "</option>";
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