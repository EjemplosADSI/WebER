<?php


namespace App\Controllers;

require(__DIR__ . '/../../vendor/autoload.php');

use App\Models\Fotos;
use App\Models\GeneralFunctions;

class FotosController
{
    private array $dataFotos;

    public function __construct(array $_FORM)
    {
        $this->dataFotos = array();
        $this->dataFotos['id'] = $_FORM['id'] ?? NULL;
        $this->dataFotos['nombre'] = $_FORM['nombre'] ?? NULL;
        $this->dataFotos['descripcion'] = $_FORM['descripcion'] ?? NULL;
        $this->dataFotos['producto_id'] = $_FORM['producto_id'] ?? 0;
        $this->dataFotos['ruta'] = $_FORM['nameFoto'] ?? '';
        $this->dataFotos['estado'] = $_FORM['estado'] ?? 'Activo';
    }

    static public function search(array $data)
    {
        try {
            $result = Fotos::search($data['query']);
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

    static public function searchForID(array $data)
    {
        try {
            $result = Fotos::searchForId($data['id']);
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
            $result = Fotos::getAll();
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

    static public function activate(int $id)
    {
        try {
            $ObjFoto = Fotos::searchForId($id);
            $ObjFoto->setEstado("Activo");
            if ($ObjFoto->update()) {
                header("Location: ../../views/modules/fotos/index.php");
            } else {
                header("Location: ../../views/modules/fotos/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception', $e, 'error');
        }
    }

    static public function inactivate(int $id)
    {
        try {
            $ObjFoto = Fotos::searchForId($id);
            $ObjFoto->setEstado("Inactivo");
            if ($ObjFoto->update()) {
                header("Location: ../../views/modules/fotos/index.php");
            } else {
                header("Location: ../../views/modules/fotos/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception', $e, 'error');
        }
    }

    static public function selectFoto(array $params = [])
    {
        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "foto_id";
        $params['name'] = $params['name'] ?? "foto_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrFoto = array();
        if ($params['where'] != "") {
            $base = "SELECT * FROM fotos WHERE ";
            $arrFoto = Fotos::search($base . $params['where']);
        } else {
            $arrFoto = Fotos::getAll();
        }

        $htmlSelect = "<select " . (($params['isMultiple']) ? "multiple" : "") . " " . (($params['isRequired']) ? "required" : "") . " id= '" . $params['id'] . "' name='" . $params['name'] . "' class='" . $params['class'] . "'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (count($arrFoto) > 0) {
            /* @var $arrFoto Fotos[] */
            foreach ($arrFoto as $foto)
                if (!FotosController::fotoIsInArray($foto->getId(), $params['arrExcluir']))
                    $htmlSelect .= "<option " . (($foto != "") ? (($params['defaultValue'] == $foto->getId()) ? "selected" : "") : "") . " value='" . $foto->getId() . "'>" . $foto->getNombre() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    public static function fotoIsInArray($idFoto, $ArrFotos)
    {
        if (count($ArrFotos) > 0) {
            foreach ($ArrFotos as $Foto) {
                if ($Foto->getId() == $idFoto) {
                    return true;
                }
            }
        }
        return false;
    }

    public function create($withFiles)
    {
        try {
            if (!empty($withFiles)) {
                $fotoProducto = $withFiles['foto'];
                $resultUpload = GeneralFunctions::subirArchivo($fotoProducto, "views/public/uploadFiles/photos/products/");

                if ($resultUpload != false) {
                    $this->dataFotos['ruta'] = $resultUpload;
                    $Foto = new Fotos ($this->dataFotos);
                    if ($Foto->insert()) {
                        unset($_SESSION['frmFotos']);
                        header("Location: ../../views/modules/fotos/index.php?respuesta=success&mensaje=Foto Creada Correctamente");
                    }
                }
            } else {
                GeneralFunctions::logFile('Error foto no encontrada');
                header("Location: ../../views/modules/fotos/create.php?respuesta=error&mensaje=Foto no encontrada");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception', $e, 'error');
        }
    }

    public function edit($withFiles = null)
    {
        try {
            if (!empty($withFiles)) {
                $rutaFoto = $withFiles['foto'];
                if ($rutaFoto['error'] == 0) { //Si la foto se selecciono correctamente
                    $resultUpload = GeneralFunctions::subirArchivo($rutaFoto, "views/public/uploadFiles/photos/products/");
                    if ($resultUpload != false) {
                        GeneralFunctions::eliminarArchivo("views/public/uploadFiles/photos/products/" . $this->dataFotos['ruta']);
                        $this->dataFotos['ruta'] = $resultUpload;
                    }
                }
            }
            if (!empty($this->dataFotos['ruta'])) {
                $foto = new Fotos($this->dataFotos);
                if ($foto->update()) {
                    unset($_SESSION['frmFotos']);
                }
                header("Location: ../../views/modules/fotos/show.php?id=" . $foto->getId() . "&respuesta=success&mensaje=Foto Actualizada");
            } else {
                GeneralFunctions::logFile('Error Foto Vaciá: ');
            }

        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception', $e, 'error');
        }
    }

}