<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index3.html" class="brand-link">
        <img src="<?= $baseURL ?>/views/public/img/weber-icon.png"
             alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light"><?= $_ENV['ALIASE_SITE'] ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 d-flex">
            <div class="image align-middle">
                <img src="<?= $baseURL ?>/views/public/img/user.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="d-flex flex-column">
                <div class="info">
                    <a href="<?= "$baseURL/views/modules/usuarios/show.php?id=" .$_SESSION['UserInSession']['id']?>" class="d-block">
                        <?= $_SESSION['UserInSession']['nombres'] ?>
                    </a>
                </div>
                <div class="info">
                    <a href="#" class="d-block">
                        <?= $_SESSION['UserInSession']['rol'] ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="<?= $baseURL; ?>/views/index.php" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Inicio
                        </p>
                    </a>
                </li>
                <li class="nav-header">Modulos Principales</li>
                <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'usuarios') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'usuarios') ? 'active' : '' ?>">
                        <i class="nav-icon far fa-user"></i>
                        <p>
                            Usuarios
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/usuarios/index.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gestionar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/usuarios/create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrar</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'categorias') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'categorias') ? 'active' : '' ?>">
                        <i class="nav-icon fa fa-sitemap"></i>
                        <p>
                            Categorias
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/categorias/index.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gestionar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/categorias/create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrar</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'productos') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'productos') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-dolly"></i>
                        <p>
                            Productos
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/productos/index.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gestionar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/productos/create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrar</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'fotos') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'fotos') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-photo-video"></i>
                        <p>
                            Fotos
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/fotos/index.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gestionar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/fotos/create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrar</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'ventas') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'ventas') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-store"></i>
                        <p>
                            Ventas
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/ventas/index.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gestionar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/ventas/create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrar</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'compras') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'compras') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-shopping-basket"></i>
                        <p>
                            Compras
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/compras/index.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gestionar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/compras/create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrar</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>

        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>