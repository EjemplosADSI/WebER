<?php

namespace App\Models;

use Dotenv\Dotenv;
use Dotenv\Environment\Adapter\EnvConstAdapter;
use Dotenv\Environment\Adapter\ServerConstAdapter;
use Dotenv\Environment\DotenvFactory;
use Exception;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use NumberFormatter;
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

            $factory = new DotenvFactory([new EnvConstAdapter(), new ServerConstAdapter()]);
            $dotenv = Dotenv::create(__DIR__ ."/../../",null,$factory);
            $dotenv->load();
            $dotenv->required($requiredVars)->notEmpty();
            $dotenv->required($integerVars)->isInteger();
            return true;
        }catch (Exception $re){
            GeneralFunctions::logFile("Carga de variables de entorno fallo.",$re,'error');
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
                GeneralFunctions::logFile('Archivo No Subido',['descripcion' => "Error en la carpeta..",$archivos->error]);
                return false;
            }
        }else{
            GeneralFunctions::logFile('Archivo No Subido',['descripcion' => "Error en la carpeta..",$archivos->error]);
            return false;
        }
    }

    /**
     * @param $File
     * @param $Ruta
     * @return bool|string
     */
    static function eliminarArchivo($Ruta) : bool
    {
        if (file_exists(__DIR__."/../../".$Ruta)) {
            unlink(__DIR__."/../../".$Ruta);
            return true;
        } else {
            GeneralFunctions::logFile("Archivo No Eliminado",['El archivo no fue encontrado en la ruta: '.__DIR__."/../../".$Ruta,'error']);
            return false;
        }
    }

    static function formatCurrency($currency = 0){
        $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
        return numfmt_format_currency($fmt, $currency, "COP");
    }

    /**
     * @param $title (titulo del log)
     * @param $description (array)
     * @param string $type (debug, info, notice, warning, error, critical, alert, emergency)
     */
    public static function logFile($title, $description = array(), $type = 'error')
    {
        try {
            $log = new Logger('General');

            //format: default [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
            $formatter = new LineFormatter(
                null,
                null, // Datetime format
                true, // allowInlineLineBreaks option, default false
                true  // discard empty Square brackets in the end, default false
            );

            $debugHandler = new StreamHandler(GeneralFunctions::$PathLogFile, Logger::DEBUG);
            $debugHandler->setFormatter($formatter);
            $log->pushHandler($debugHandler);

            if (is_a($description, 'Exception')) {
                $log->$type($title);
                $log->$type("Archivo: ".$description->getFile());
                $log->$type("Line: ".$description->getLine());
                $log->$type("Mensaje: ".$description->getMessage());
                $log->$type(print_r($description->getTrace(), true));
                echo "<table class='xdebug-error xe-uncaught-exception' 
                        dir='ltr' border='1' cellspacing='0' cellpadding='1'>" .
                    $description->xdebug_message.
                    "</table>";
            } else {
                $log->$type($title, $description);
            }
        } catch (Exception $ex) {
            echo "Error general: ".$ex->getMessage();
        }
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


    /**
     * @param string $type (error, info, warning, success)
     * @param string $mensaje
     * @param string $title
     * @return string
     */
    static function getAlertDialog (string $type = 'info', string $mensaje = '', string $title = "" ) : string
    {
        $alert = ""; $icon = ""; $title = "";
        $type = ($type == 'error') ? 'danger' : $type;

        if($type === 'danger'){
            $icon = "ban";
            $title = "Error: ";
            $mensaje = "Ha ocurrido el siguiente error: ".$mensaje;
        }else if($type === 'info'){
            $icon = "info";
            $title = "Información: ";
        }else if($type === 'warning'){
            $icon = "exclamation-triangle";
            $title = "Advertencia: ";
            $mensaje = "Alerta: ".$mensaje;
        }else if($type === 'success'){
            $icon = "check";
            $title = "Solicitud Procesada: ";
        }

        $alert .= "<div class='alert alert-$type alert-dismissible'>";
        $alert .= "    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
        $alert .= "    <h5><i class='icon fas fa-$icon'></i>".$title."</h5>";
        $alert .= $mensaje."!";
        $alert .= "</div>";

        return $alert;
    }
}