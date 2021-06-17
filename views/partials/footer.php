<footer class="main-footer">
    <div class="row">
        <div class="col-4">
            <strong>Copyright &copy; <?= date('Y') ?> <a href="<?= $baseURL; ?>"><?= $_ENV['ALIASE_SITE'] ?></a>.</strong>
            Derechos Reservados.
        </div>
        <div class="col-4">
            <?php include_once ("social_buttons.php")?>
        </div>
        <div class="col-4">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> <?= date('Y') ?>
            </div>
        </div>
    </div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->

</aside>
<!-- /.control-sidebar -->