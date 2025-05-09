<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?= $data['page_title']; ?></title>
    <link href="Assets/css/styles.css" rel="stylesheet">
    <link href="Assets/css/info.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" />
</head>
<body>
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="<?= base_url(); ?>/dashboard"> RRHH</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" type="button">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..."
                    aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i
                        class="fas fa-search"></i></button>
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <?= $_SESSION['userData']['nombres']; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Perfil</a></li>

                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        <button id="logoutButton" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                        </button>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="">
        <div id="">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="<?= base_url(); ?>/dashboard">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <?php if (!empty($_SESSION['permisos'][USUARIOS]['leer'])) { ?>
                            <a class="nav-link" href="<?= base_url(); ?>/Usuarios">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Usuarios
                            </a>
                        <?php } ?>
                        <div class="sb-sidenav-menu-heading">Personal</div>
                        <?php if (!empty($_SESSION['permisos'][USUARIOS]['leer'])) { ?>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Empleados
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                        <?php } ?>
                        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= base_url(); ?>/aprobacion/Reclutamiento">Reclutamiento</a>
                                <a class="nav-link" href="<?= base_url(); ?>/personal">Personal </a>
                                <a class="nav-link" href="<?= base_url(); ?>/personal/personalBaja">Personal de Baja</a>
                                <a class="nav-link" href="<?= base_url(); ?>/aprobacion">Aprobaciones</a>
                                <a class="nav-link" href="<?= base_url(); ?>/aprobacion/reprobados">Reprobados</a>

                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages"
                            aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Configuracion
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <?php if (!empty($_SESSION['permisos'][LISTADO]['leer'])) { ?>
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                        data-bs-target="#pagesCollapseAuth" aria-expanded="false"
                                        aria-controls="pagesCollapseAuth">
                                        Listados
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                <?php } ?>
                                <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="<?= base_url(); ?>/Listado/Areas">Area Laboral</a>
                                        <a class="nav-link" href="<?= base_url(); ?>/Listado/Empresa">Empresas</a>
                                        <a class="nav-link" href="<?= base_url(); ?>/Listado/Puestos">Puestos</a>
                                        <?php if (!empty($_SESSION['permisos'][12]['leer'])) { ?>
                                            <a class="nav-link" href="<?= base_url(); ?>/Listado/Jefes">Jefes</a>
                                        <?php } ?>
                                        <a class="nav-link" href="<?= base_url(); ?>/Listado/Departamento">Departamento
                                            Laboral</a>
                                        <a class="nav-link" href="<?= base_url(); ?>/Listado/LN">Lineas de Negocio</a>
                                        <a class="nav-link" href="<?= base_url(); ?>/Listado/Dimension">Dimensiones</a>
                                        <?php if (!empty($_SESSION['permisos'][11]['leer'])) { ?>
                                            <a class="nav-link" href="<?= base_url(); ?>/Listado/Documentos">Documentos</a>
                                        <?php } ?>
                                        <a class="nav-link" href="<?= base_url(); ?>/Listado/Academicos">Academicos</a>
                                    </nav>
                                </div>
                                <?php if (!empty($_SESSION['permisos'][ROLES]['leer'])) { ?>
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                        data-bs-target="#pagesCollapseError" aria-expanded="false"
                                        aria-controls="pagesCollapseError">
                                        Roles
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                <?php } ?>
                                <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="<?= base_url(); ?>/Roles/Roles">Roles General</a>
                                        <a class="nav-link" href="404.html">404 Page</a>
                                        <a class="nav-link" href="500.html">500 Page</a>
                                    </nav>
                                </div>
                            </nav>
                        </div>
                        <div class="sb-sidenav-menu-heading">Addons</div>
                        <a class="nav-link" href="charts.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Charts
                        </a>
                        <a class="nav-link" href="tables.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Tables
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Version System:</div>
                    1.0.1
                </div>
            </nav>
        </div>