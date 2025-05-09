<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?= $data['page_title']; ?></title>

    <!-- CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?= media(); ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <!-- Font Awesome y LineIcons -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.lineicons.com/5.0/lineicons.css" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Otros estilos -->
    <link rel="stylesheet" href="<?= media(); ?>/plugins/daterangepicker/daterangepicker.css">
    <link href="Assets/css/info.css" rel="stylesheet">
    <link href="Assets/css/styles.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <hr>
            <div class="d-flex mb-4">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-user-multiple-4"></i>
                </button>
                <div class="sidebar-logo">
                    <?php if (!empty($_SESSION['permisos'][1]['leer'])) { ?>
                        <a href="<?= base_url(); ?>/Dashboard">C.C. RECURSOS HUMANOS</a>
                    <?php } else { ?>
                        <a href="">C.C. RECURSOS HUMANOS</a>
                    <?php } ?>
                </div>
            </div>

            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <?php if (!empty($_SESSION['permisos'][2]['leer'])) { ?>
                        <a href="<?= base_url(); ?>/Personal" class="sidebar-link">
                            <i class="lni lni-user-4"></i>
                            <span>Personal</span>
                        </a>
                    <?php } ?>
                </li>
                <?php if (!empty($_SESSION['permisos'][9]['leer'])) { ?>
                    <li class="sidebar-item">
                        <a href="<?= base_url(); ?>/Aprobacion/Reclutamiento" class="sidebar-link">
                            <i class="fa-solid fa-users-rectangle"></i>
                            <span>Reclutamiento</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if (!empty($_SESSION['permisos'][10]['leer'])) { ?>
                    <li class="sidebar-item">
                        <a href="<?= base_url(); ?>/Personal/personalBaja" class="sidebar-link">
                            <i class="fa-solid fa-user-xmark"></i>
                            <span>Bajas</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if (!empty($_SESSION['permisos'][11]['leer'])) { ?>
                    <li class="sidebar-item">
                        <a href="<?= base_url(); ?>/Aprobacion" class="sidebar-link">
                            <i class="fa-solid fa-pause"></i>
                            <span>En Espera</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if (!empty($_SESSION['permisos'][12]['leer'])) { ?>
                    <li class="sidebar-item">
                        <a href="<?= base_url(); ?>/Aprobacion/Reprobados" class="sidebar-link">
                            <i class="fa-solid fa-file-excel"></i>
                            <span>Rechazos</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if (!empty($_SESSION['permisos'][13]['leer'])) { ?>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                            data-bs-target="#listado" aria-expanded="false" aria-controls="listado">
                            <i class="lni lni-menu-cheesburger"></i>
                            <span>Listado Config</span>
                        </a>
                        <ul id="listado" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="<?= base_url(); ?>/Listado/Areas" class="sidebar-link">Area Laboral</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="<?= base_url(); ?>/Listado/Puestos" class="sidebar-link">Puestos</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="<?= base_url(); ?>/Listado/Jefes" class="sidebar-link">jefes</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="<?= base_url(); ?>/Listado/Departamento" class="sidebar-link">Departamento</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="<?= base_url(); ?>/Listado/LN" class="sidebar-link">Linea de Negocio</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="<?= base_url(); ?>/Listado/Dimension" class="sidebar-link">Dimensiones</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="<?= base_url(); ?>/Listado/Documentos" class="sidebar-link">Doc. Expedientes</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="<?= base_url(); ?>/Listado/Academicos" class="sidebar-link">Doc. Academicos</a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>

                <?php if (!empty($_SESSION['permisos'][13]['leer'])) { ?>
                <?php } ?>

                <?php if (!empty($_SESSION['permisos'][14]['leer']) || !empty($_SESSION['permisos'][15]['leer']) || !empty($_SESSION['permisos'][16]['leer']) || !empty($_SESSION['permisos'][17]['leer'])) { ?>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                            data-bs-target="#vacaciones" aria-expanded="false" aria-controls="vacaciones">
                            <i class="lni lni-life-guard-tube-1"></i>
                            <span>Vacaciones</span>
                        </a>
                        <ul id="vacaciones" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <?php if (!empty($_SESSION['permisos'][14]['leer'])) { ?>
                                <li class="sidebar-item">
                                    <a href="<?= base_url(); ?>/Vacaciones_Revision/Vacaciones_Revision" class="sidebar-link">
                                        <i class="lni lni-agenda"></i>
                                        <span>Vacaciones General</span>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (!empty($_SESSION['permisos'][15]['leer'])) { ?>
                                <li class="sidebar-item">
                                    <a href="<?= base_url(); ?>/Vacaciones_Revision/Vacaciones" class="sidebar-link">
                                        <i class="lni lni-agenda"></i>
                                        <span>Solicitudes Vacaciones </span>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (!empty($_SESSION['permisos'][16]['leer'])) { ?>
                                <li class="sidebar-item">
                                    <a href="<?= base_url(); ?>/Vacaciones_Revision/VacacionesOperativa" class="sidebar-link">
                                        <i class="lni lni-agenda"></i>
                                        <span>Vacaciones Operativa </span>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (!empty($_SESSION['permisos'][17]['leer'])) { ?>
                                <li class="sidebar-item">
                                    <a href="<?= base_url(); ?>/Vacaciones_Revision/Vacaciones_Periodos" class="sidebar-link">
                                        <i class="lni lni-agenda"></i>
                                        <span>Vacaciones Periodos</span>
                                    </a>
                                </li>

                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>

                <?php if (!empty($_SESSION['permisos'][18]['leer']) || !empty($_SESSION['permisos'][19]['leer'])) { ?>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                            data-bs-target="#historial" aria-expanded="false" aria-controls="historial">
                            <i class="lni lni-bar-chart-4"></i>
                            <span>Bitacoras</span>
                        </a>
                        <ul id="historial" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <?php if (!empty($_SESSION['permisos'][18]['leer'])) { ?>
                                <li class="sidebar-item">
                                    <a href="<?= base_url(); ?>/Bitacoras/Historial_Empleados" class="sidebar-link">Historial
                                        Empleado</a>
                                </li>
                            <?php } ?>
                            <?php if (!empty($_SESSION['permisos'][19]['leer'])) { ?>
                                <li class="sidebar-item">
                                    <a href="<?= base_url(); ?>/Bitacoras/Movimientos_Empleados"
                                        class="sidebar-link">Movimientos Empleados</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>

                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#registros" aria-expanded="false" aria-controls="registros">
                        <i class="fa-solid fa-microchip"> </i>
                        <span>Sistemas</span>
                    </a>
                    <ul id="registros" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="<?= base_url(); ?>/Sistemas/Registros" class="sidebar-link">
                                <i class="lni lni-target-user"></i>
                                Actividad Usuarios
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?= base_url(); ?>/Sistemas/BackUps" class="sidebar-link">
                                <i class="lni lni-target-user"></i>
                                BackUps
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <?php if (!empty($_SESSION['permisos'][7]['leer'])) { ?>
                                <a href="<?= base_url(); ?>/Usuarios" class="sidebar-link">
                                    <i class="lni lni-target-user"></i>
                                    <span>Usuarios</span>
                                </a>
                            <?php } ?>
                        </li>
                        <li class="sidebar-item">
                            <?php if (!empty($_SESSION['permisos'][8]['leer'])) { ?>
                                <a href="<?= base_url(); ?>/Roles" class="sidebar-link">
                                    <i class="fa fa-solid fa-wrench"></i>
                                    <span>Roles</span>
                                </a>
                            <?php } ?>
                        </li>
                    </ul>
                </li>

            </ul>
            <div class="sidebar-footer">
                <a href="#" id="Perfil" class="sidebar-link" data-bs-toggle="modal" data-bs-target="#perfilModal">
                    <i class="lni lni-gear-1"></i>
                    <span>Perfil</span>
                </a>
            </div>
            <div class="sidebar-footer">
                <a href="#" id="Perfil" class="sidebar-link" data-bs-toggle="modal" data-bs-target="#exitModal">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Modal Perfil del Usuario -->
        <div class="modal fade" id="perfilModal" tabindex="-1" aria-labelledby="perfilModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-black text-white">
                        <h1 class="modal-title fs-5" id="perfilModalLabel">Perfil del Usuario</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <!-- Columna Izquierda -->
                                <div class="col-md-4 text-center border-end">
                                    <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png"
                                        class="img-fluid rounded-circle mb-3" width="100" alt="Perfil">
                                    <h5><?= $_SESSION['PersonalData']['nombre_completo'] ?></h5>
                                    <p><strong>No. Empleado:</strong>
                                        <?= $_SESSION['PersonalData']['no_empleado'] ?? 'N/A' ?></p>
                                    <p><strong>Correo:</strong>
                                        <?= $_SESSION['PersonalData']['correo_empresarial'] ?? 'N/A' ?></p>
                                    <p><strong>Puesto:</strong>
                                        <?= $_SESSION['PersonalData']['nombre_puesto'] ?? 'N/A' ?></p>
                                </div>

                                <!-- Columna Derecha -->
                                <div class="col-md-8">
                                    <h2 class="mb-4">Actualizar Contraseña</h2>
                                    <form id="formActualizarPassword" autocomplete="off">
                                        <div class="mb-3">
                                            <input type="hidden" class="form-control" name="correo_empresarial"
                                                id="correo_empresarial"
                                                value="<?= $_SESSION['PersonalData']['correo_empresarial'] ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Contraseña Actual</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password_new" class="form-label">Nueva Contraseña</label>
                                            <input type="password" class="form-control" id="password_new"
                                                name="password_new" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password_confirmacion" class="form-label">Confirmar Nueva
                                                Contraseña</label>
                                            <input type="password" class="form-control" id="password_confirmacion"
                                                name="password_confirmacion" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Código de Verificación -->
        <div class="modal fade" id="codigoModal" tabindex="-1" aria-labelledby="codigoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="codigoModalLabel">Verificación de Código</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <form id="tokenForm">
                        <input type="hidden" id="correoToken" name="correo_empresarial">
                        <p>Hemos enviado un token a tu correo. Ingresa el código recibido para continuar.</p>
                        <input type="text" id="token" name="token" class="form-control text-center my-3"
                            placeholder="Código de verificación" required>
                        <div class="modal-footer">
                            <button type="submit" id="tokenSubmitBtn" class="btn btn-primary btn-validar">
                                Validar
                                <span id="tokenSpinner" class="spinner-border spinner-border-sm d-none"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="exitModal" tabindex="-1" aria-labelledby="exitModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0 rounded-4">
                    <div class="modal-header bg-danger text-white rounded-top-4">
                        <h5 class="modal-title" id="exitModalLabel">
                            <i class="bi bi-box-arrow-right me-2"></i> ¿Cerrar Sesión?
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 3rem;"></i>
                        <p class="mt-3 fs-5 mb-0">¿Estás seguro de que deseas cerrar sesión?</p>
                    </div>
                    <div class="modal-footer justify-content-center border-0 pb-4">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="button" id="logoutButton" class="btn btn-danger px-4">
                            <i class="lni lni-exit me-1"></i> Cerrar Sesión
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script src="<?= base_url(); ?>/Assets/js/functions_password.js"></script>