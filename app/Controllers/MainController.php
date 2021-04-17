<?php

namespace App\Controllers;
require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;

if(session_status() == PHP_SESSION_NONE){ //Si la session no ha iniciado
    session_start();
}

if (!empty($_GET['controller'])){

    unset( $_SESSION['frm'.ucfirst($_GET['controller'])] );
    $_SESSION['frm'.ucfirst($_GET['controller'])] = $_POST; //Guarda Valores en la sesion por si hay erroes en el formulario

    $nameController = 'App\Controllers\\'.(ucfirst($_GET['controller'])."Controller");
    if(class_exists($nameController)){
        $controller = new $nameController($_POST);
        if (!empty($_GET['action']) and method_exists($controller, $_GET['action'])) {
            if(!empty($_GET['id'])){
                $controller->{$_GET['action']}($_GET['id']);
            }else if (!empty($_POST['request']) && $_POST['request'] == "ajax") {
                //echo call_user_func_array(array($controller, $_GET['action']), $_POST);
                echo $controller->{$_GET['action']}($_POST);
            }else{
                if(!empty($_FILES)){
                    $controller->{$_GET['action']}($_FILES);
                }else{
                    $controller->{$_GET['action']}();
                }
            }
        }else{
            GeneralFunctions::logFile('Action no encontrada',['descripcion' => "La accion ".$_GET['action']." no fue encontrada en el controlador ".$nameController]);
            header('Location: ' . strtok($_SERVER['HTTP_REFERER'], '?')."?respuesta=error&mensaje=Action no encontrada");
        }
    }else{
        GeneralFunctions::logFile('Clase no encontrada',['descripcion' => "La clase ".$nameController." no fue encontrada "]);
        header('Location: ' . strtok($_SERVER['HTTP_REFERER'], '?')."?respuesta=error&mensaje=Solicitud Errónea");
    }
}else{
    GeneralFunctions::logFile('Controlador no recibido',['descripcion' => "Falta parametro de controlador en MainController"]);
    header('Location: ' . strtok($_SERVER['HTTP_REFERER'], '?')."?respuesta=error&mensaje=Solicitud sin Parámetros");
}