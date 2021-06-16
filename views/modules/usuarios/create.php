 <?php
require("../../partials/routes.php");
require_once("../../partials/check_login.php");

use App\Controllers\DepartamentosController;
use App\Controllers\MunicipiosController;
use App\Models\GeneralFunctions;
use Carbon\Carbon;

$nameModel = "Usuario";
$nameForm = 'frmCreate'.$nameModel;
$pluralModel = $nameModel.'s';
$frmSession = $_SESSION[$nameForm] ?? NULL;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Crear <?= $nameModel ?></title>
    <?php require("../../partials/head_imports.php"); ?>
</head>
<body class="hold-transition sidebar-mini">

<!-- Site wrapper -->
<div class="wrapper">
    <?php require("../../partials/navbar_customization.php"); ?>

    <?php require("../../partials/sliderbar_main_menu.php"); ?>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Crear un Nuevo <?= $nameModel ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item"><a href="index.php"><?= $pluralModel ?></a></li>
                            <li class="breadcrumb-item active">Crear</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Generar Mensaje de alerta -->
            <?= (!empty($_GET['respuesta'])) ? GeneralFunctions::getAlertDialog($_GET['respuesta'], $_GET['mensaje']) : ""; ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Horizontal Form -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-user"></i> &nbsp; Información del <?= $nameModel ?></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="card-refresh"
                                            data-source="create.php" data-source-selector="#card-refresh-content"
                                            data-load-on-init="false"><i class="fas fa-sync-alt"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                class="fas fa-expand"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <!-- form start -->
                                <form class="form-horizontal" enctype="multipart/form-data" method="post" id="<?= $nameForm ?>"
                                      name="<?= $nameForm ?>"
                                      action="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=create">
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <div class="form-group row">
                                                <label for="nombres" class="col-sm-2 col-form-label">Nombres</label>
                                                <div class="col-sm-10">
                                                    <input required type="text" class="form-control" id="nombres" name="nombres"
                                                           placeholder="Ingrese sus nombres" value="<?= $frmSession['nombres'] ?? '' ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="apellidos" class="col-sm-2 col-form-label">Apellidos</label>
                                                <div class="col-sm-10">
                                                    <input required type="text" class="form-control" id="apellidos"
                                                           name="apellidos" placeholder="Ingrese sus apellidos"
                                                           value="<?= $frmSession['apellidos'] ?? '' ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="tipo_documento" class="col-sm-2 col-form-label">
                                                    Tipo Documento</label>
                                                <div class="col-sm-10">
                                                    <select id="tipo_documento" name="tipo_documento" class="custom-select">
                                                        <option <?= (!empty($frmSession['tipo_documento']) && $frmSession['tipo_documento'] == "C.C") ? "selected" : ""; ?> value="C.C">Cedula de Ciudadania</option>
                                                        <option <?= (!empty($frmSession['tipo_documento']) && $frmSession['tipo_documento'] == "C.E") ? "selected" : ""; ?> value="C.E">Cedula de Extranjeria</option>
                                                        <option <?= (!empty($frmSession['tipo_documento']) && $frmSession['tipo_documento'] == "T.I") ? "selected" : ""; ?> value="T.I">Tarjeta de Identidad</option>
                                                        <option <?= (!empty($frmSession['tipo_documento']) && $frmSession['tipo_documento'] == "R.C") ? "selected" : ""; ?> value="R.C">Registro Civil</option>
                                                        <option <?= (!empty($frmSession['tipo_documento']) && $frmSession['tipo_documento'] == "Pasaporte") ? "selected" : ""; ?> value="Pasaporte">Pasaporte</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="documento" class="col-sm-2 col-form-label">Documento</label>
                                                <div class="col-sm-10">
                                                    <input required type="number" minlength="6" class="form-control"
                                                           id="documento" name="documento" placeholder="Ingrese su documento"
                                                           value="<?= $frmSession['documento'] ?? '' ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="telefono" class="col-sm-2 col-form-label">Telefono</label>
                                                <div class="col-sm-10">
                                                    <input required type="number" minlength="6" class="form-control"
                                                           id="telefono" name="telefono" placeholder="Ingrese su telefono"
                                                           value="<?= $frmSession['telefono'] ?? '' ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="direccion" class="col-sm-2 col-form-label">Direccion</label>
                                                <div class="col-sm-10">
                                                    <input required type="text" class="form-control" id="direccion"
                                                           name="direccion" placeholder="Ingrese su direccion"
                                                           value="<?= $frmSession['direccion'] ?? '' ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="municipio_id" class="col-sm-2 col-form-label">Municipio</label>
                                                <div class="col-sm-5">
                                                    <?= DepartamentosController::selectDepartamentos(
                                                        array(
                                                            'id' => 'departamento_id',
                                                            'name' => 'departamento_id',
                                                            'defaultValue' => '15', //Boyacá
                                                            'class' => 'form-control select2bs4 select2-info',
                                                            'where' => "estado = 'Activo'"
                                                        )
                                                    )
                                                    ?>
                                                </div>
                                                <div class="col-sm-5 ">
                                                    <?= MunicipiosController::selectMunicipios(array (
                                                        'id' => 'municipio_id',
                                                        'name' => 'municipio_id',
                                                        'defaultValue' => (!empty($frmSession['municipio_id'])) ? $frmSession['municipio_id'] : '',
                                                        'class' => 'form-control select2bs4 select2-info',
                                                        'where' => "departamento_id = 15 and estado = 'Activo'"))
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="fecha_nacimiento" class="col-sm-2 col-form-label">Fecha Nacimiento</label>
                                                <div class="col-sm-10">
                                                    <input required type="date" max="<?= Carbon::now()->subYear(12)->format('Y-m-d') ?>" class="form-control" id="fecha_nacimiento"
                                                           name="fecha_nacimiento" placeholder="Ingrese su Fecha de Nacimiento"
                                                           value="<?= $frmSession['fecha_nacimiento'] ?? '' ?>">
                                                </div>
                                            </div>
                                            <?php if ($_SESSION['UserInSession']['rol'] == 'Administrador'){ ?>
                                                <div class="form-group row">
                                                    <label for="user" class="col-sm-2 col-form-label">Usuario</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="user" name="user"
                                                               placeholder="Ingrese su Usuario" value="<?= $frmSession['user'] ?? '' ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="password" class="col-sm-2 col-form-label">Password</label>
                                                    <div class="col-sm-10">
                                                        <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese su Usuario">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="rol" class="col-sm-2 col-form-label">Rol</label>
                                                    <div class="col-sm-10">
                                                        <select required id="rol" name="rol" class="custom-select">
                                                            <option <?= (!empty($frmSession['rol']) && $frmSession['rol'] == "Administrador") ? "selected" : ""; ?> value="Administrador">Administrador</option>
                                                            <option <?= (!empty($frmSession['rol']) && $frmSession['rol'] == "Empleado") ? "selected" : ""; ?> value="Empleado">Empleado</option>
                                                            <option <?= (!empty($frmSession['rol']) && $frmSession['rol'] == "Cliente") ? "selected" : ""; ?> value="Cliente">Cliente</option>
                                                            <option <?= (!empty($frmSession['rol']) && $frmSession['rol'] == "Proveedor") ? "selected" : ""; ?> value="Proveedor">Proveedor</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                                                    <div class="col-sm-10">
                                                        <select required id="estado" name="estado" class="custom-select">
                                                            <option <?= ( !empty($frmSession['estado']) && $frmSession['estado'] == "Activo") ? "selected" : ""; ?> value="Activo">Activo</option>
                                                            <option <?= ( !empty($frmSession['estado']) && $frmSession['estado'] == "Inactivo") ? "selected" : ""; ?> value="Inactivo">Inactivo</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="info-box">
                                                <div class="imageupload panel panel-primary">
                                                    <div class="panel-heading clearfix">
                                                        <h5 class="panel-title pull-left">Foto de Perfil</h5>
                                                    </div>
                                                    <div class="file-tab panel-body">
                                                        <label class="btn btn-default btn-file">
                                                            <span>Seleccionar</span>
                                                            <!-- The file is stored here. -->
                                                            <input type="file" id="foto" name="foto">
                                                        </label>
                                                        <button type="button" class="btn btn-default">Eliminar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <button id="frmName" name="frmName" value="<?= $nameForm ?>" type="submit" class="btn btn-info">Enviar</button>
                                    <a href="index.php" role="button" class="btn btn-default float-right">Cancelar</a>
                                    <!-- /.card-footer -->
                                </form>
                            </div>
                            <!-- /.card-body -->

                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php require('../../partials/footer.php'); ?>
</div>
<!-- ./wrapper -->
<?php require('../../partials/scripts.php'); ?>
<script>
    $(function() {
        $('#departamento_id').on('change', function() {
            $.post("../../../app/Controllers/MainController.php?controller=Municipios&action=selectMunicipios", {
                isMultiple: false,
                isRequired: true,
                id: "municipio_id",
                nombre: "municipio_id",
                defaultValue: "",
                class: "form-control select2bs4 select2-info",
                where: "departamento_id = "+$('#departamento_id').val()+" and estado = 'Activo'",
                request: 'ajax'
            }, function(e) {
                if (e)
                    console.log(e);
                    $("#municipio_id").html(e).select2({ height: '100px'});
            });
        });
        $('.btn-file span').html('Seleccionar');
    });
</script>
</body>
</html>
