<?php require('../vendor/autoload.php'); ?>
<?php
$dotenv = Dotenv\Dotenv::create("../");
$dotenv->load();

$baseURL = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/".getenv('ROOT_FOLDER');
$adminlteURL = $baseURL."/vendor/almasaeed2010/adminlte";
?>

<!-- Font Awesome -->
<link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/fontawesome-free/css/all.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="<?= $adminlteURL ?>/dist/css/adminlte.min.css">
<!-- Google Font: Source Sans Pro -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
