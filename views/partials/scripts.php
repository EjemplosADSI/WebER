<!-- jQuery -->
<script src="<?= $adminlteURL ?>/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= $adminlteURL ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= $adminlteURL ?>/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= $adminlteURL ?>/dist/js/demo.js"></script>
<!-- Select2 -->
<script src="<?= $adminlteURL ?>/plugins/select2/js/select2.full.min.js"></script>

<!-- Page script -->
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    })
</script>