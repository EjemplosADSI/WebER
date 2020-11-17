<?php
use App\Models\GeneralFunctions;
    //$RutaAbsoluta = "\WebER\views\index.php"; //https://www.php.net/manual/es/regexp.reference.escape.php
    //$RutaRelativa = "../index.php";

    //Carga las librerias importadas del composer
require(__DIR__ .'/../../vendor/autoload.php');
    //__DIR__ => D:\laragon\www\WebER\views\partials
if(GeneralFunctions::loadEnv(['ROOT_FOLDER'])){
    $baseURL = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/".$_ENV['ROOT_FOLDER'];
//https://localhost/WebER/
    $adminlteURL = $baseURL."/vendor/almasaeed2010/adminlte";
//https://localhost/WebER/vendor/almasaeed2010/adminlte
}else{
    GeneralFunctions::logFile('Error al cargar variables del entorno');
    die();
}
?>