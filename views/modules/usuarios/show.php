<?php
require("../../partials/routes.php");
require("../../../app/Controllers/UsuariosController.php");

use App\Controllers\UsuariosController; ?>
<!DOCTYPE html>
<html>
<head>
    <title><?= getenv('TITLE_SITE') ?> | Datos del Usuario</title>
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
                        <h1>Informacion del Usuario</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/Views/">WebER</a></li>
                            <li class="breadcrumb-item active">Inicio</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <?php if(!empty($_GET['respuesta'])){ ?>
                <?php if ($_GET['respuesta'] == "error"){ ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                            Error al consultar el usuario: <?= ($_GET['mensaje']) ?? "" ?>
                    </div>
                <?php } ?>
            <?php } else if (empty($_GET['id'])) { ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    Faltan criterios de busqueda <?= ($_GET['mensaje']) ?? "" ?>
                </div>
            <?php } ?>

            <!-- Horizontal Form -->
            <div class="card card-info">
                <?php if(!empty($_GET["id"]) && isset($_GET["id"])){
                    $DataUsuario = UsuariosController::searchForID($_GET["id"]);
                    if(!empty($DataUsuario)){
                ?>
                <div class="card-header">
                    <h3 class="card-title"><?= $DataUsuario->getNombres()  ?></h3>
                </div>
                <div class="card-body">
                    <p>

                        <strong><i class="fas fa-book mr-1"></i> Nombres y Apellidos</strong>
                        <p class="text-muted">
                            <?= $DataUsuario->getNombres()." ".$DataUsuario->getApellidos() ?>
                        </p>
                        <hr>
                        <strong><i class="fas fa-user mr-1"></i> Documento</strong>
                        <p class="text-muted"><?= $DataUsuario->getTipoDocumento().": ".$DataUsuario->getDocumento() ?></p>
                        <hr>
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Direccion</strong>
                        <p class="text-muted"><?= $DataUsuario->getDireccion() ?></p>
                        <hr>
                        <strong><i class="fas fa-phone mr-1"></i> Telefono</strong>
                        <p class="text-muted"><?= $DataUsuario->getTelefono() ?></p>
                        <hr>
                        <strong><i class="far fa-file-alt mr-1"></i> Estado y Rol</strong>
                        <p class="text-muted"><?= $DataUsuario->getEstado()." - ".$DataUsuario->getRol() ?></p>
                    </p>

                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-auto mr-auto">
                            <a role="button" href="index.php" class="btn btn-success float-right" style="margin-right: 5px;">
                                <i class="fas fa-tasks"></i> Gestionar Usuarios
                            </a>
                        </div>
                        <div class="col-auto">
                            <a role="button" href="create.php" class="btn btn-primary float-right" style="margin-right: 5px;">
                                <i class="fas fa-plus"></i> Crear Usuario
                            </a>
                        </div>
                    </div>
                </div>
                    <?php }else{ ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-ban"></i> Error!</h5>
                            No se encontro ningun registro con estos parametros de busqueda <?= ($_GET['mensaje']) ?? "" ?>
                        </div>
                    <?php }
                } ?>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php require ('../../partials/footer.php');?>
</div>
<!-- ./wrapper -->
<?php require ('../../partials/scripts.php');?>
</body>
</html>
