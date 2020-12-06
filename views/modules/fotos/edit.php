<?php
require("../../partials/routes.php");
require_once("../../partials/check_login.php");
require("../../../app/Controllers/FotosController.php");

use App\Controllers\FotosController;
use App\Controllers\ProductosController;
use App\Models\Fotos;
use App\Models\GeneralFunctions;
use Carbon\Carbon;

$nameModel = "Foto";
$pluralModel = $nameModel.'s';
$frmSession = $_SESSION['frm'.$pluralModel] ?? NULL;

?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Editar <?= $nameModel ?></title>
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
                        <h1>Editar <?= $nameModel ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item"><a href="index.php"><?= $pluralModel ?></a></li>
                            <li class="breadcrumb-item active">Editar</li>
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
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-box"></i>&nbsp; Información del <?= $nameModel ?></h3>
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
                            <?php if (!empty($_GET["id"]) && isset($_GET["id"])) { ?>
                                <p>
                                <?php
                                $DataFoto = FotosController::searchForID(["id" => $_GET["id"]]);
                                /* @var $DataFoto Fotos */
                                if (!empty($DataFoto)) {
                                    ?>
                                    <div class="card-body">
                                        <!-- form start -->
                                        <form class="form-horizontal" enctype="multipart/form-data" method="post" id="frmEdit<?= $nameModel ?>"
                                              name="frmEdit<?= $nameModel ?>"
                                              action="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=edit">
                                            <div class="row">
                                                <div class="col-sm-10">
                                                    <input id="id" name="id" value="<?= $DataFoto->getId(); ?>"
                                                           hidden required="required" type="text">
                                                    <div class="form-group row">
                                                        <label for="nombres" class="col-sm-2 col-form-label">Nombres</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" id="nombre"
                                                                   name="nombre" value="<?= $DataFoto->getNombre(); ?>"
                                                                   placeholder="Ingrese el nombre">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="descripcion" class="col-sm-2 col-form-label">Descripción</label>
                                                        <div class="col-sm-10">
                                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4"
                                                              placeholder="Ingrese una descripción"><?= $DataFoto->getDescripcion()?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="producto_id" class="col-sm-2 col-form-label">Producto</label>
                                                        <div class="col-sm-10">
                                                            <?= ProductosController::selectProducto(
                                                                array (
                                                                    'id' => 'producto_id',
                                                                    'name' => 'producto_id',
                                                                    'defaultValue' => $DataFoto->getProductoId() ?? '',
                                                                    'class' => 'form-control select2bs4 select2-info',
                                                                    'where' => "estado = 'Activo'"
                                                                )
                                                            )
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                                                        <div class="col-sm-10">
                                                            <select id="estado" name="estado" class="custom-select">
                                                                <option <?= ($DataFoto->getEstado() == "Activo") ? "selected" : ""; ?>
                                                                        value="Activo">Activo
                                                                </option>
                                                                <option <?= ($DataFoto->getEstado() == "Inactivo") ? "selected" : ""; ?>
                                                                        value="Inactivo">Inactivo
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
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
                                                                    <input value="<?= $DataFoto->getRuta(); ?>" type="file" id="foto" name="foto">
                                                                </label>
                                                                <button type="button" class="btn btn-default">Eliminar</button>
                                                            </div>
                                                            <div class="panel-footer">
                                                                <?php if(!empty($DataFoto->getRuta())){?>
                                                                    <img id="thumbFoto" src="../../public/uploadFiles/photos/products/<?= $DataFoto->getRuta(); ?>"
                                                                         alt="Sin Foto de Perfil" class="thumbnail" style="max-width: 250px; max-height: 250px">
                                                                <?php } ?>
                                                                <input type="hidden" name="nameFoto" id="nameFoto" value="<?= $DataFoto->getRuta() ?? '' ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <button type="submit" class="btn btn-info">Enviar</button>
                                            <a href="index.php" role="button" class="btn btn-default float-right">Cancelar</a>
                                        </form>
                                    </div>
                                    <!-- /.card-body -->

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
                                </p>
                            <?php } ?>
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
        $('#foto').on("change", function(){
            $( "#thumbFoto" ).remove();
        });
    });
</script>
</body>
</html>
