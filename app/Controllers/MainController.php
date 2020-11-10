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
            if($_GET['action'] === "activate" or $_GET['action'] === "inactivate"){
                $controller->{$_GET['action']}($_GET['id']);
            }else if($_GET['action'] === "selectAjax"){
                echo $controller->{$_GET['action']}($_POST);
            }else{
                if(!empty($_FILES)){
                    $controller->{$_GET['action']}($_FILES);
                }else{
                    $controller->{$_GET['action']}();
                }
            }
        }else{
            header('Location: ' . strtok($_SERVER['HTTP_REFERER'], '?')."?respuesta=error&mensaje=Action no encontrada");
        }
    }else{
        header('Location: ' . strtok($_SERVER['HTTP_REFERER'], '?')."?respuesta=error&mensaje=Solicitud Errónea");
    }
}else{
    header('Location: ' . strtok($_SERVER['HTTP_REFERER'], '?')."?respuesta=error&mensaje=Solicitud sin Parámetros");
}