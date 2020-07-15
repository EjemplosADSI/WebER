<?php
    require_once("../../../app/Controllers/UsuariosController.php");
    require("../../partials/routes.php");
    use App\Controllers\UsuariosController;
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= getenv('TITLE_SITE') ?> | Crear Venta</title>
    <?php require("../../partials/head_imports.php"); ?>
</head>
<body class="hold-transition sidebar-mini">

<!-- Site wrapper -->
<div class="wrapper">
    <?php require("../../partials/navbar_customization.php"); ?>

    <?php require("../../partials/sliderbar_main_menu.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <?php if(!empty($_GET['respuesta'])){ ?>
            <?php if ($_GET['respuesta'] != "correcto"){ ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    Error al crear la venta: <?= $_GET['mensaje'] ?>
                </div>
            <?php } ?>
        <?php } ?>

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Crear una Nueva Venta</h1>
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
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-shopping-cart"></i> Información de la Venta</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="create.php" data-source-selector="#card-refresh-content" data-load-on-init="false"><i class="fas fa-sync-alt"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                </div>
                            </div>

                                <div class="card-body">
                                    <form class="form-horizontal" method="post" id="frmCreateVenta" name="frmCreateVenta" action="../../../app/Controllers/VentasController.php?action=create">
                                    <div class="form-group row">
                                        <label for="cliente_id" class="col-sm-2 col-form-label">Cliente</label>
                                        <div class="col-sm-10">
                                            <?= UsuariosController::selectUsuario(false,
                                                true,
                                                'cliente_id',
                                                'cliente_id',
                                                '',
                                                'form-control select2bs4 select2-info',
                                                "rol = 'Cliente' and estado = 'Activo'")
                                            ?>
                                        </div>
                                    </div>
                                        <div class="form-group row">
                                            <label for="empleado_id" class="col-sm-2 col-form-label">Empleado</label>
                                            <div class="col-sm-10">
                                                <?= UsuariosController::selectUsuario(false,
                                                    true,
                                                    'empleado_id',
                                                    'empleado_id',
                                                    '',
                                                    'form-control select2bs4 select2-info',
                                                    "rol = 'Empleado' and estado = 'Activo'")
                                                ?>
                                            </div>
                                        </div>
                                        <hr>
                                    <!-- /.card-body -->
                                        <button type="submit" class="btn btn-info">Enviar</button>
                                        <a href="index.php" role="button" class="btn btn-default float-right">Cancelar</a>
                                    </form>
                                </div>
                        </div>
                        <!-- /.card -->

                    </div>
                    <div class="row">
                        <!-- /.col (left) -->
                        <div class="col-md-6">
                        </div>
                        <!-- /.col (right) -->
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
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
