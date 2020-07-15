<?php

namespace App\Models;

require(__DIR__ .'/../../vendor/autoload.php');
class GeneralFunctions
{
    static function SubirArchivo($File, $Ruta){
        $archivos = new upload($File);
        if ($archivos->uploaded){
            $archivos->file_new_name_body = (date('H-M-s')."-".$archivos->file_src_name_body);
            $archivos->Process($Ruta);
            if($archivos->processed){
                return $archivos->file_dst_name;
            }else{
                echo "Archivo No Subido, Error en la carpeta..".$archivos->error;
                return false;
            }
        }else{
            echo "Archivo No Subido, Error en la carpeta..".$archivos->error;
            return false;
        }
    }

    static function console ($data, $type = 'log', $typePrint = 'simple' ){
        echo '<script>';
        if ($typePrint == 'errorStack'){
            echo 'console.'.$type.'('. json_encode($data->getMessage()) .');';
            echo 'console.'.$type.'('. json_encode($data->getTrace()) .');';
        }else{
            $dataPrint = json_encode($data);
            echo 'console.'.$type.'('. $dataPrint .')';
        }
        echo '</script>';
    }
}