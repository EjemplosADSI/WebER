<?php
//Carga las librerias importadas del composer
require(__DIR__ .'/../../vendor/autoload.php');

if(session_status() == PHP_SESSION_NONE){ //Si la session no ha iniciado
    session_start();
}
if (empty($_SESSION['UserInSession'])){
    header("Location: ".$GLOBALS['baseURL']."/views/modules/site/login.php?respuesta=error&mensaje=Debes Iniciar Session");
}