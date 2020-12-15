<?php
require("../../partials/routes.php");
require_once("../../partials/check_login.php");
require("../../../app/Controllers/VentasController.php");

use App\Controllers\VentasController;
use App\Models\Ventas;
use App\Models\GeneralFunctions;

$nameModel = "Venta";
$pluralModel = $nameModel.'s';
$frmSession = $_SESSION['frm'.$pluralModel] ?? NULL;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Datos de la <?= $nameModel ?></title>
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
                        <h1>Información de la <?= $nameModel ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item"><a href="index.php"><?= $pluralModel ?></a></li>
                            <li class="breadcrumb-item active">Ver</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Generar Mensajes de alerta -->
            <?= (!empty($_GET['respuesta'])) ? GeneralFunctions::getAlertDialog($_GET['respuesta'], $_GET['mensaje']) : ""; ?>
            <?= (empty($_GET['id'])) ? GeneralFunctions::getAlertDialog('error', 'Faltan Criterios de Búsqueda') : ""; ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Horizontal Form -->
                        <div class="card card-green">
                            <?php if (!empty($_GET["id"]) && isset($_GET["id"])) {
                                $DataVentas = VentasController::searchForID(["id" => $_GET["id"]]);
                                /* @var $DataVentas Ventas */
                                if (!empty($DataVentas)) {
                                    ?>
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-shopping-cart"></i> &nbsp; Ver
                                            Información de <?= $DataVentas->getNumeroSerie() ?>
                                            -<?= $DataVentas->getId() ?></h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="card-refresh"
                                                    data-source="show.php" data-source-selector="#card-refresh-content"
                                                    data-load-on-init="false"><i class="fas fa-sync-alt"></i></button>
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                        class="fas fa-expand"></i></button>
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                    data-toggle="tooltip" title="Collapse">
                                                <i class="fas fa-minus"></i></button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove"
                                                    data-toggle="tooltip" title="Remove">
                                                <i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p>

                                            <strong><i class="fas fa-sort-numeric-down mr-1"></i> Numero</strong>
                                        <p class="text-muted">
                                            <?= $DataVentas->getNumeroSerie() . "-" . $DataVentas->getId(); ?>
                                        </p>
                                        <hr>
                                        <strong><i class="fas fa-user-ninja mr-1"></i> Cliente</strong>
                                        <p class="text-muted"><?= $DataVentas->getCliente()->getNombres() . " " . $DataVentas->getCliente()->getApellidos() ?></p>
                                        <hr>
                                        <strong><i class="far fa-user mr-1"></i> Empleado</strong>
                                        <p class="text-muted"><?= $DataVentas->getEmpleado()->getNombres() . " " . $DataVentas->getEmpleado()->getApellidos() ?></p>
                                        <hr>
                                        <strong><i class="far fa-calendar mr-1"></i> Fecha Venta</strong>
                                        <p class="text-muted"><?= $DataVentas->getFechaVenta(); ?></p>
                                        <hr>
                                        <strong><i class="fas fa-money-bill mr-1"></i> Monto</strong>
                                        <p class="text-muted"><?= GeneralFunctions::formatCurrency($DataVentas->getMonto()); ?></p>
                                        <hr>
                                        <strong><i class="fas fa-cog mr-1"></i> Estado</strong>
                                        <p class="text-muted"><?= $DataVentas->getEstado(); ?></p>
                                        </p>

                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-auto mr-auto">
                                                <a role="button" href="index.php" class="btn btn-success float-right"
                                                   style="margin-right: 5px;">
                                                    <i class="fas fa-tasks"></i> Gestionar <?= $pluralModel ?>
                                                </a>
                                            </div>
                                            <div class="col-auto">
                                                <a role="button" href="create.php" class="btn btn-primary float-right"
                                                   style="margin-right: 5px;">
                                                    <i class="fas fa-plus"></i> Crear <?= $nameModel ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                                        No se encontro ningun registro con estos parametros de
                                        busqueda <?= ($_GET['mensaje']) ?? "" ?>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php require('../../partials/footer.php'); ?>
</div>
<!-- ./wrapper -->
<?php require('../../partials/scripts.php'); ?>
</body>
</html>
