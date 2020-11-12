<?php

namespace App\Models;

use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Verot\Upload\Upload;

final class GeneralFunctions
{

    public static $PathLogFile = __DIR__."/../../resources/logs/general.log";
    /**
     * @param array $requiredVars
     * @param array $integerVars
     */
    static function loadEnv (array $requiredVars = [], array $integerVars = []){
        try {
            $dotenv = Dotenv::create(__DIR__ ."/../../");
            $dotenv->load();
            $dotenv->required($requiredVars)->notEmpty();
            $dotenv->required($integerVars)->isInteger();
        }catch (\Exception $re){
            echo "Variables faltantes o vaciás: ";
            throw new \RuntimeException($re->getMessage());
        }
    }

    /**
     * @param $File
     * @param $Ruta
     * @return bool|string
     */
    static function subirArchivo($File, $Ruta)
    {
        $archivos = new Upload($File);
        if ($archivos->uploaded){
            $archivos->file_new_name_body = (date('H-M-s')."-".$archivos->file_src_name_body);
            $archivos->Process(__DIR__."/../../".$Ruta);
            if($archivos->processed){
                return $archivos->file_dst_name;
            }else{
                GeneralFunctions::logFile("Archivo No Subido, Error en la carpeta..",$archivos->error,'error');
                return false;
            }
        }else{
            GeneralFunctions::logFile("Archivo No Subido, Error en la carpeta..",$archivos->error,'error');
            return false;
        }
    }

    /**
     * @param $File
     * @param $Ruta
     * @return bool|string
     */
    static function eliminarArchivo($Ruta)
    {
        if (file_exists(__DIR__."/../../".$Ruta)) {
            unlink(__DIR__."/../../".$Ruta);
        } else {
            GeneralFunctions::logFile("Archivo No Eliminado",'El archivo no fue encontrado en la ruta: '.__DIR__."/../../".$Ruta,'error');
            return false;
        }
    }

    /**
     * @param $title (titulo del log)
     * @param $description (array)
     * @param string $type (debug, info, notice, warning, error, critical, alert, emergency)
     */
    static function logFile($title, $description, $type = 'error'){
        $log = new Logger('General');
        $log->pushHandler(new StreamHandler(GeneralFunctions::$PathLogFile, Logger::DEBUG));
        $log->$type($title, (array) $description);
    }

    /**
     * @param object|string $data $e Exception Object
     * @param string $type (info, warn, log, error)
     * @param string $typePrint (simple or errorStack)
     */
    static function console ($data, string $type = 'log', string $typePrint = 'simple' ) : void
    {
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