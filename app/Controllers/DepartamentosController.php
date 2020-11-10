<?php


namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Departamentos;


class DepartamentosController
{

    static public function searchForID($id)
    {
        try {
            return Departamentos::searchForId($id);
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
        }
    }

    static public function getAll()
    {
        try {
            return Departamentos::getAll();
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'log', 'errorStack');
        }
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