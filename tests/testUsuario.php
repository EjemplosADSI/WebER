<?php
require "..\app\Models\Usuarios.php";

use App\Models\Usuarios;

$usuario = Usuarios::searchForId(1);
$usuario->setPassword("123456");
$usuario->update();