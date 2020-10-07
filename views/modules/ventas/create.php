<?php
require_once("../../../app/Controllers/DetalleVentasController.php");
require_once("../../../app/Controllers/VentasController.php");
require_once("../../../app/Controllers/UsuariosController.php");
require_once("../../../app/Controllers/ProductosController.php");
require("../../partials/routes.php");
require_once("../../partials/check_login.php");

use App\Controllers\ProductosController;
use App\Controllers\UsuariosController;
use App\Controllers\VentasController;
use App\Models\DetalleVentas;

?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Crear Venta</title>
    <?php require("../../partials/head_imports.php"); ?>
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-responsive/css/responsive.bootstrap4.css">
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-buttons/css/buttons.bootstrap4.css">
</head>
<body class="hold-transition sidebar-mini">

<!-- Site wrapper -->
<div class="wrapper">
    <?php require("../../partials/navbar_customization.php"); ?>

    <?php require("../../partials/sliderbar_main_menu.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <?php if (!empty($_GET['respuesta'])) { ?>
            <?php if ($_GET['respuesta'] != "correcto") { ?>
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
                <!-- /.row -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-shopping-cart"></i> &nbsp; Información de la
                                    Venta</h3>
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

                            <div class="card-body">
                                <form class="form-horizontal" method="post" id="frmCreateVenta" name="frmCreateVenta"
                                      action="../../../app/Controllers/VentasController.php?action=create">

                                    <?php
                                    $dataVenta = null;
                                    if (!empty($_GET['id'])) {
                                        $dataVenta = VentasController::searchForID($_GET['id']);
                                    }
                                    ?>

                                    <div class="form-group row">
                                        <label for="cliente_id" class="col-sm-4 col-form-label">Cliente</label>
                                        <div class="col-sm-8">
                                            <?= UsuariosController::selectUsuario(false,
                                                true,
                                                'cliente_id',
                                                'cliente_id',
                                                (!empty($dataVenta)) ? $dataVenta->getClienteId()->getId() : '',
                                                'form-control select2bs4 select2-info',
                                                "rol = 'Cliente' and estado = 'Activo'")
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="empleado_id" class="col-sm-4 col-form-label">Empleado</label>
                                        <div class="col-sm-8">
                                            <?= UsuariosController::selectUsuario(false,
                                                true,
                                                'empleado_id',
                                                'empleado_id',
                                                (!empty($dataVenta)) ? $dataVenta->getEmpleadoId()->getId() : '',
                                                'form-control select2bs4 select2-info',
                                                "rol = 'Empleado' and estado = 'Activo'")
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                    if (!empty($dataVenta)) {
                                        ?>
                                        <div class="form-group row">
                                            <label for="numero_serie" class="col-sm-4 col-form-label">Codigo
                                                Factura</label>
                                            <div class="col-sm-8">
                                                <?= $dataVenta->getNumeroSerie() ?>-<?= $dataVenta->getId() ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="numero_serie" class="col-sm-4 col-form-label">Fecha
                                                Venta</label>
                                            <div class="col-sm-8">
                                                <?= $dataVenta->getFechaVenta() ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="numero_serie" class="col-sm-4 col-form-label">Monto</label>
                                            <div class="col-sm-8">
                                                <?= $dataVenta->getMonto() ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <hr>
                                    <button type="submit" class="btn btn-info">Enviar</button>
                                    <a href="index.php" role="button" class="btn btn-default float-right">Cancelar</a>
                                </form>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <div class="col-md-8">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-parachute-box"></i> &nbsp; Detalle Venta</h3>
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

                            <div class="card-body">
                                <?php if (!empty($_GET['id'])) { ?>
                                    <div class="row">
                                        <div class="col-auto mr-auto"></div>
                                        <div class="col-auto">
                                            <a role="button" href="#" data-toggle="modal" data-target="#modal-add-producto"
                                               class="btn btn-primary float-right"
                                               style="margin-right: 5px;">
                                                <i class="fas fa-plus"></i> Añadir Producto
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col">
                                        <table id="tblDetalleProducto"
                                               class="datatable table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if (!empty($_GET['id'])) {
                                                $arrDetalleVentas = DetalleVentas::search("SELECT * FROM weber.detalle_ventas WHERE ventas_id = ".$_GET['id']);
                                                if(count($arrDetalleVentas) > 0) {
                                                    /* @var $arrDetalleVentas \App\Models\DetalleVentas[] */
                                                    foreach ($arrDetalleVentas as $detalleVenta) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $detalleVenta->getId(); ?></td>
                                                            <td><?php echo $detalleVenta->getProductoId()->getNombres(); ?></td>
                                                            <td><?php echo $detalleVenta->getCantidad(); ?></td>
                                                            <td><?php echo $detalleVenta->getPrecio(); ?></td>
                                                            <td>
                                                                <a href="edit.php?id=<?php echo $detalleVenta->getId(); ?>"
                                                                   type="button" data-toggle="tooltip" title="Actualizar"
                                                                   class="btn docs-tooltip btn-primary btn-xs"><i
                                                                            class="fa fa-edit"></i></a>
                                                                <a href="show.php?id=<?php echo $detalleVenta->getId(); ?>"
                                                                   type="button" data-toggle="tooltip" title="Ver"
                                                                   class="btn docs-tooltip btn-warning btn-xs"><i
                                                                            class="fa fa-eye"></i></a>
                                                                <?php if ($detalleVenta->getEstado() != "Activo") { ?>
                                                                    <a href="../../../app/Controllers/ProductosController.php?action=activate&Id=<?php echo $detalleVenta->getId(); ?>"
                                                                       type="button" data-toggle="tooltip" title="Activar"
                                                                       class="btn docs-tooltip btn-success btn-xs"><i
                                                                                class="fa fa-check-square"></i></a>
                                                                <?php } else { ?>
                                                                    <a type="button"
                                                                       href="../../../app/Controllers/ProductosController.php?action=inactivate&Id=<?php echo $detalleVenta->getId(); ?>"
                                                                       data-toggle="tooltip" title="Inactivar"
                                                                       class="btn docs-tooltip btn-danger btn-xs"><i
                                                                                class="fa fa-times-circle"></i></a>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                }
                                            }?>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Nombres</th>
                                                <th>Precio</th>
                                                <th>Stock</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <div id="modals">
        <div class="modal fade" id="modal-add-producto">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Agregar Producto a Venta</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="../../../app/Controllers/DetalleVentasController.php?action=create" method="post">
                        <div class="modal-body">
                            <?php //var_dump($dataVenta); ?>
                            <input id="ventas_id" name="ventas_id" value="<?= !empty($dataVenta) ? $dataVenta->getId() : ''; ?>" hidden
                                   required="required" type="text">
                            <div class="form-group row">
                                <label for="producto_id" class="col-sm-4 col-form-label">Producto</label>
                                <div class="col-sm-8">
                                    <?= ProductosController::selectProducto(false,
                                        true,
                                        'producto_id',
                                        'producto_id',
                                        '',
                                        'form-control select2bs4 select2-info',
                                        "estado = 'Activo'")
                                    ?>
                                    <div id="divResultProducto">
                                        <span class="text-muted">Precio Base: </span> <span id="spPrecio"></span>,
                                        <span class="text-muted">Precio Venta: </span> <span id="spPrecioVenta"></span>,
                                        <span class="text-muted">Stock: </span> <span id="spStock"></span>.
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="cantidad" class="col-sm-4 col-form-label">Cantidad</label>
                                <div class="col-sm-8">
                                    <input required type="number" min="1" class="form-control" step="1" id="cantidad" name="cantidad"
                                           placeholder="Ingrese la cantidad">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="precio_venta" class="col-sm-4 col-form-label">Precio Unitario</label>
                                <div class="col-sm-8">
                                    <input required readonly type="number" min="1" class="form-control" id="precio_venta" name="precio_venta"
                                           placeholder="0.0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="total_producto" class="col-sm-4 col-form-label">Total Producto</label>
                                <div class="col-sm-8">
                                    <input required readonly type="number" min="1" class="form-control" id="total_producto" name="total_producto"
                                           placeholder="0.0">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>

    <?php require('../../partials/footer.php'); ?>
</div>
<!-- ./wrapper -->
<?php require('../../partials/scripts.php'); ?>
<!-- DataTables -->
<script src="<?= $adminlteURL ?>/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= $adminlteURL ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script src="<?= $adminlteURL ?>/plugins/datatables-responsive/js/dataTables.responsive.js"></script>
<script src="<?= $adminlteURL ?>/plugins/datatables-responsive/js/responsive.bootstrap4.js"></script>
<script src="<?= $adminlteURL ?>/plugins/datatables-buttons/js/dataTables.buttons.js"></script>
<script src="<?= $adminlteURL ?>/plugins/datatables-buttons/js/buttons.bootstrap4.js"></script>
<script src="<?= $adminlteURL ?>/plugins/jszip/jszip.js"></script>
<script src="<?= $adminlteURL ?>/plugins/pdfmake/pdfmake.js"></script>
<script src="<?= $adminlteURL ?>/plugins/datatables-buttons/js/buttons.html5.js"></script>
<script src="<?= $adminlteURL ?>/plugins/datatables-buttons/js/buttons.print.js"></script>
<script src="<?= $adminlteURL ?>/plugins/datatables-buttons/js/buttons.colVis.js"></script>

<script>

    $(function () {

        $("#divResultProducto").hide();

        $('#producto_id').on('select2:select', function (e) {
            var dataSelect = e.params.data;
            var dataProducto = null;
            if(dataSelect.id !== ""){
                $.post("../../../app/Controllers/ProductosController.php?action=searchForIDAjax", {idProducto: dataSelect.id}, "json")
                    .done(function( resultProducto ) {
                        dataProducto = resultProducto;
                    })
                    .fail(function(err) {
                        console.log( "Error al realizar la consulta"+err );
                    })
                    .always(function() {
                        updateDataProducto(dataProducto);
                    });
            }else{
                updateDataProducto(dataProducto);
            }
        });

        function updateDataProducto(dataProducto){
            if(dataProducto !== null){
                $("#divResultProducto").slideDown();
                $("#spPrecio").html("$"+dataProducto.precio);
                $("#spPrecioVenta").html("$"+dataProducto.precio_venta);
                $("#spStock").html(dataProducto.stock+" Unidad(es)");
                $("#cantidad").attr("max",dataProducto.stock);
                $("#precio_venta").val(dataProducto.precio_venta);
            }else{
                $("#divResultProducto").slideUp();
                $("#spPrecio").html("");
                $("#spPrecioVenta").html("");
                $("#spStock").html("");
                $("#cantidad").removeAttr("max").val('0');
                $("#precio_venta").val('0.0');
                $("#total_producto").val('0.0');
            }
        }

        $( "#cantidad" ).on( "change keyup focusout", function() {
            $("#total_producto").val($( "#cantidad" ).val() *  $("#precio_venta").val());
        });

        $('.datatable').DataTable({
            "dom": 'Bfrtip',
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "language": {
                "url": "../../public/Spanish.json" //Idioma
            },
            "buttons": [
                'copy', 'print', 'excel', 'pdf'
            ],
            "pagingType": "full_numbers",
            "responsive": true,
            "stateSave": true, //Guardar la configuracion del usuario
        });
    });
</script>


</body>
</html>
