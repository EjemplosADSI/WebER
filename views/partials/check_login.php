<?php
if(session_status() == PHP_SESSION_NONE){ //Si la session no ha iniciado
    session_start();
}
if (empty($_SESSION['UserInSession'])){
    header("Location: ".$baseURL."/views/modules/site/login.php?respuesta=error&mensaje=Debes Iniciar Session");
}