<?php
require_once("../../../app/Controllers/UsuariosController.php");
require_once("../../partials/routes.php");
require_once("../../partials/check_login.php");

use App\Controllers\CategoriasController;
use App\Controllers\ProductosController;
use App\Models\GeneralFunctions;
use App\Models\Productos;
use App\Models\Categorias;

$nameModel = "Producto";
$pluralModel = $nameModel.'s';
$frmSession = $_SESSION['frm'.$pluralModel] ?? NULL;

/* Si llega el idCategoria cargar los datos de esa categoria */
/* @var $_SESSION['idCategoria'] Categorias */
$_SESSION['idCategoria'] = !empty($_GET['idCategoria']) ? CategoriasController::searchForID(['id' => $_GET['idCategoria']]) : NULL;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Gestión de <?= $pluralModel ?></title>
    <?php require("../../partials/head_imports.php"); ?>
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= $GLOBALS['adminlteURL'] ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="<?= $GLOBALS['adminlteURL'] ?>/plugins/datatables-responsive/css/responsive.bootstrap4.css">
    <link rel="stylesheet" href="<?= $GLOBALS['adminlteURL'] ?>/plugins/datatables-buttons/css/buttons.bootstrap4.css">
</head>
<body class="hold-transition sidebar-mini">

<!-- Site wrapper -->
<div class="wrapper">
    <?php require_once("../../partials/navbar_customization.php"); ?>

    <?php require_once("../../partials/sliderbar_main_menu.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Pagina Principal</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $GLOBALS['baseURL']; ?>/views/"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item active"><?= $pluralModel ?></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Generar Mensajes de alerta -->
            <?= (!empty($_GET['respuesta'])) ? GeneralFunctions::getAlertDialog($_GET['respuesta'], $_GET['mensaje']) : ""; ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Default box -->
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-boxes"></i> &nbsp; Gestionar <?= $pluralModel ?></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="card-refresh"
                                            data-source="index.php" data-source-selector="#card-refresh-content"
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
                                <div class="row">
                                    <div class="col-auto mr-auto"></div>
                                    <div class="col-auto">
                                        <a role="button" href="create.php" class="btn btn-primary float-right"
                                           style="margin-right: 5px;">
                                            <i class="fas fa-plus"></i> Crear <?= $nameModel ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <table id="tbl<?= $pluralModel ?>" class="datatable table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nombres</th>
                                                <th>Precio</th>
                                                <th>Ganancia</th>
                                                <th>Venta</th>
                                                <th>Stock</th>
                                                <th>Categoría</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $arrProductos = array();
                                            if(!empty($_SESSION['idCategoria'])){
                                                $arrProductos = $_SESSION['idCategoria']->getProductosCategoria();
                                            }else{
                                                $arrProductos = ProductosController::getAll();
                                            }

                                            /* @var $arrProductos Productos[] */
                                            foreach ($arrProductos as $producto) {
                                                ?>
                                                <tr>
                                                    <td><?= $producto->getId(); ?></td>
                                                    <td><?= $producto->getNombre(); ?></td>
                                                    <td><?= GeneralFunctions::formatCurrency($producto->getPrecio()); ?></td>
                                                    <td><?= $producto->getPorcentajeGanancia(); ?>%</td>
                                                    <td><?= GeneralFunctions::formatCurrency($producto->getPrecioVenta()); ?></td>
                                                    <td><?= $producto->getStock(); ?></td>
                                                    <td><?= $producto->getCategoria()->getNombre(); ?></td>
                                                    <td><?= $producto->getEstado(); ?></td>
                                                    <td>
                                                        <a href="edit.php?id=<?= $producto->getId(); ?>"
                                                           type="button" data-toggle="tooltip" title="Actualizar"
                                                           class="btn docs-tooltip btn-primary btn-xs"><i
                                                                    class="fa fa-edit"></i></a>
                                                        <a href="show.php?id=<?= $producto->getId(); ?>"
                                                           type="button" data-toggle="tooltip" title="Ver"
                                                           class="btn docs-tooltip btn-warning btn-xs"><i
                                                                    class="fa fa-eye"></i></a>
                                                        <a href="../fotos/index.php?idProducto=<?= $producto->getId(); ?>"
                                                           type="button" data-toggle="tooltip" title="Gestionar Fotos"
                                                           class="btn docs-tooltip btn-success btn-xs"><i
                                                                    class="fa fa-photo-video"></i></a>
                                                        <?php if ($producto->getEstado() != "Activo") { ?>
                                                            <a href="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=activate&id=<?= $producto->getId(); ?>"
                                                               type="button" data-toggle="tooltip" title="Activar"
                                                               class="btn docs-tooltip btn-success btn-xs"><i
                                                                        class="fa fa-check-square"></i></a>
                                                        <?php } else { ?>
                                                            <a type="button"
                                                               href="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=inactivate&id=<?= $producto->getId(); ?>"
                                                               data-toggle="tooltip" title="Inactivar"
                                                               class="btn docs-tooltip btn-danger btn-xs"><i
                                                                        class="fa fa-times-circle"></i></a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Nombres</th>
                                                <th>Precio</th>
                                                <th>Ganancia</th>
                                                <th>Venta</th>
                                                <th>Stock</th>
                                                <th>Categoría</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                Pie de Página.
                            </div>
                            <!-- /.card-footer-->
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
<!-- Scripts requeridos para las datatables -->
<?php require('../../partials/datatables_scripts.php'); ?>
</body>
</html>
