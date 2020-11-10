<?php


namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Municipios;

class MunicipiosController
{

    static public function searchForID($id)
    {
        try {
            return Municipios::searchForId($id);
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'error', 'errorStack');
        }
    }

    static public function getAll()
    {
        try {
            return Municipios::getAll();
        } catch (\Exception $e) {
            GeneralFunctions::console($e, 'log', 'errorStack');
        }
    }

    public function selectAjax(array $data){
        return MunicipiosController::selectMunicipios(
            $data["isMultiple"],
            $data["isRequired"],
            $data["id"],
            $data["nombre"],
            $data["defaultValue"],
            $data["class"],
            $data["where"]);
    }

    static public function selectMunicipios($isMultiple = false,
                                            $isRequired = true,
                                            $id = "municipio_id",
                                            $nombre = "municipio_id",
                                            $defaultValue = "",
                                            $class = "form-control",
                                            $where = "",
                                            $arrExcluir = array())
    {
        $arrMunicipios = array();
        if ($where != "") {
            $base = "SELECT * FROM municipios WHERE ";
            $arrMunicipios = Municipios::search($base . ' ' . $where);
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