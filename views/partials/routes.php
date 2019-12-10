<?php require('../../../vendor/autoload.php'); ?>
<?php
$dotenv = Dotenv\Dotenv::create("../../../");
$dotenv->load();

$baseURL = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/".getenv('ROOT_FOLDER');
//https://localhost/WebER/
$adminlteURL = $baseURL."/vendor/almasaeed2010/adminlte";
//https://localhost/WebER/vendor/almasaeed2010/adminlte
?>