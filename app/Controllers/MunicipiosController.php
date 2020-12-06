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

    static public function selectMunicipios(array $params = [])
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

        $arrMunicipios = array();
        if ($params['where'] != "") {
            $base = "SELECT * FROM municipios WHERE ";
            $arrMunicipios = Municipios::search($base . $params['where']);
        } else {
            $arrMunicipios = Municipios::getAll();
        }
        $htmlSelect = "<select " . (($params['isMultiple']) ? "multiple" : "") . " " . (($params['isRequired']) ? "required" : "") . " id= '" . $params['id'] . "' name='" . $params['name'] . "' class='" . $params['class'] . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (count($arrMunicipios) > 0) {
            /* @var $arrMunicipios Municipios[] */
            foreach ($arrMunicipios as $municipio)
                if (!MunicipiosController::municipioIsInArray($municipio->getId(), $params['arrExcluir']))
                    $htmlSelect .= "<option " . (($municipio != "") ? (($params['defaultValue'] == $municipio->getId()) ? "selected" : "") : "") . " value='" . $municipio->getId() . "'>" . $municipio->getNombre() . "</option>";
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