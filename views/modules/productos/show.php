<?php
require("../../partials/routes.php");
require_once("../../partials/check_login.php");
require("../../../app/Controllers/ProductosController.php");

use App\Controllers\ProductosController;
use App\Models\Fotos;
use App\Models\GeneralFunctions;
use App\Models\Productos;

$nameModel = "Producto";
$pluralModel = $nameModel.'s';
$frmSession = $_SESSION['frm'.$pluralModel] ?? NULL;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Datos del <?= $nameModel ?></title>
    <?php require("../../partials/head_imports.php"); ?>
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/ekko-lightbox/ekko-lightbox.css">
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
                        <h1>Informacion del <?= $nameModel ?></h1>
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
                    <?php if (!empty($_GET["id"]) && isset($_GET["id"])) { ?>
                        <?php
                        $DataProducto = ProductosController::searchForID(["id" => $_GET["id"]]);
                        /* @var $DataProducto Productos */
                        if (!empty($DataProducto)) {
                            ?>
                            <div class="col-12 col-sm-12">
                                <div class="card card-success card-tabs">
                                    <div class="card-header p-0 pt-1">
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
                                        <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                                            <li class="pt-2 px-3"><h3 class="card-title"><?= $DataProducto->getNombre() ?></h3></li>
                                            <li class="nav-item">
                                                <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Información</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#custom-tabs-two-profile" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Galería</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-two-tabContent">
                                            <div class="tab-pane fade show active" id="custom-tabs-two-home" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
                                                <p>
                                                    <strong><i class="fas fa-book mr-1"></i> Nombre</strong>
                                                <p class="text-muted">
                                                    <?= $DataProducto->getNombre() ?>
                                                </p>
                                                <hr>
                                                <strong><i class="fas fa-dollar-sign mr-1"></i> Precio Base</strong>
                                                <p class="text-muted"><?= GeneralFunctions::formatCurrency($DataProducto->getPrecio()) ?></p>
                                                <hr>
                                                <strong><i class="fas fa-dollar-sign mr-1"></i> Precio Venta</strong>
                                                <p class="text-muted"><?= GeneralFunctions::formatCurrency($DataProducto->getPrecioVenta()); ?></p>
                                                <hr>
                                                <strong><i class="fas fa-dollar-sign mr-1"></i> Porcentaje Ganancia</strong>
                                                <p class="text-muted"><?= $DataProducto->getPorcentajeGanancia() ?>%</p>
                                                <hr>
                                                <strong><i class="fas fa-archive mr-1"></i> Stock</strong>
                                                <p class="text-muted"><?= $DataProducto->getStock() ?></p>
                                                <hr>
                                                <strong><i class="fas fa-sitemap mr-1"></i> Categoría</strong>
                                                <p class="text-muted"><?= $DataProducto->getCategoria()->getNombre() ?></p>
                                                <hr>
                                                <strong><i class="far fa-file-alt mr-1"></i> Estado</strong>
                                                <p class="text-muted"><?= $DataProducto->getEstado() ?></p>
                                                </p>
                                            </div>
                                            <div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
                                                <div class="row">
                                                    <?php
                                                    $arrFotos = $DataProducto->getFotosProducto();
                                                    /* @var $arrFotos Fotos[] */
                                                    foreach ($arrFotos as $foto){
                                                    ?>
                                                    <div class="col-sm-2">
                                                        <a href="../../public/uploadFiles/photos/products/<?= $foto->getRuta() ?>" data-toggle="lightbox" data-title="<?= $foto->getNombre()?>" data-gallery="gallery">
                                                            <img src="../../public/uploadFiles/photos/products/<?= $foto->getRuta() ?>" class="img-fluid mb-2" alt="<?= $foto->getDescripcion()?>"/>
                                                        </a>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
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
                                                <a role="button" href="edit.php?id=<?= $DataProducto->getId(); ?>" class="btn btn-primary float-right"
                                                   style="margin-right: 5px;">
                                                    <i class="fas fa-edit"></i> Editar <?= $nameModel ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card -->
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
                        <?php } ?>
                    <?php } ?>
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
<!-- Ekko Lightbox -->
<script src="<?= $adminlteURL ?>/plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
<!-- Page specific script -->
<script>
    $(function () {
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });
    })
</script>
</body>
</html>
