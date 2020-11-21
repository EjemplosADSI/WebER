<?php


namespace App\Controllers;

require(__DIR__ . '/../../vendor/autoload.php');

use App\Models\GeneralFunctions;
use App\Models\Municipios;

class MunicipiosController
{

    static public function searchForID(array $data)
    {
        try {
            $result = Municipios::searchForId($data['id']);
            if (!empty($data['request']) and $data['request'] === 'ajax' and !empty($result)) {
                header('Content-type: application/json; charset=utf-8');
                $result = json_encode($result->jsonSerialize());
            }
            return $result;
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception', $e, 'error');
        }
        return null;
    }

    static public function getAll(array $data = null)
    {
        try {
            $result = Municipios::getAll();
            if (!empty($data['request']) and $data['request'] === 'ajax') {
                header('Content-type: application/json; charset=utf-8');
                $result = json_encode($result);
            }
            return $result;
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception', $e, 'error');
        }
        return null;
    }

    static public function selectMunicipios($isMultiple = false,
                                            $isRequired = true,
                                            $id = "municipio_id",
                                            $nombre = "municipio_id",
                                            $defaultValue = "",
                                            $class = "form-control",
                                            $where = "",
                                            $arrExcluir = array(),
                                            $request = 'html')
    {
        $arrMunicipios = array();
        if ($where != "") {
            $arrMunicipios = Municipios::search("SELECT * FROM municipios WHERE " . $where);
        } else {
            $arrMunicipios = Municipios::getAll();
        }
        $htmlSelect = "<select " . (($isMultiple) ? "multiple" : "") . " " . (($isRequired) ? "required" : "") . " id= '" . $id . "' name='" . $nombre . "' class='" . $class . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (count($arrMunicipios) > 0) {
            /* @var $arrMunicipios Municipios[] */
            foreach ($arrMunicipios as $municipio)
                if (!MunicipiosController::municipioIsInArray($municipio->getId(), $arrExcluir))
                    $htmlSelect .= "<option " . (($municipio != "") ? (($defaultValue == $municipio->getId()) ? "selected" : "") : "") . " value='" . $municipio->getId() . "'>" . $municipio->getNombre() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    private static function municipioIsInArray($idMunicipio, $ArrMunicipios)
    {
        if (count($ArrMunicipios) > 0) {
            foreach ($ArrMunicipios as $Usuario) {
                if ($Usuario->getId() == $idMunicipio) {
                    return true;
                }
            }
        }
        return false;
    }

}