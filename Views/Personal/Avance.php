<?php headerAdmin($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/info.css" />


<div class="main p-3">
    <div class="container-fluid">
        <h1 class="mt-4">Información del Empleado</h1>
        <div class="card mb-4">
            <div class="card-header info-section text-white">
                <i class="fas fa-user"></i> Info Basica del Empleado
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nombres:</strong> <?= $data['personal']['nombres']; ?></p>
                        <p><strong>Apellidos:</strong> <?= $data['personal']['apellidos']; ?></p>
                        <p><strong>Fecha Ingreso:</strong> <?= $data['personal']['fecha_ingreso']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Identificacion:</strong> <?= $data['personal']['identificacion']; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>Codigo del Empleado:</strong> <?= $data['personal']['codigo_empleado']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">Informacion Personal del Empleado</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <?php if (!empty($_SESSION['permisos'][2]['leer'])) { ?>
                            <button class="btn btn-primary" onclick="window.location.href='<?= base_url(); ?>/Info/Mostrar/<?= $data['personal']['identificacion']; ?>'">
                                Ver Información
                            </button>
                        <?php } else { ?>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Ver Informacion
                            </button>
                        <?php } ?>

                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">Uniformes del Empleado</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <?php if (!empty($_SESSION['permisos'][4]['leer'])) { ?>
                            <button class="btn text-white btn-succes" onclick="window.location.href='<?= base_url(); ?>/Uniformes/Mostrar/<?= $data['personal']['id_empleado']; ?>'">
                                Ver Uniformes
                            </button>
                        <?php } else { ?>
                            <button type="button" class="btn text-white btn-succes" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Ver Uniformes
                            </button>
                        <?php } ?>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body">Expedientes</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">

                        <?php if (!empty($_SESSION['permisos'][5]['leer'])) { ?>
                            <button class="btn text-white btn-danger" onclick="window.location.href='<?= base_url(); ?>/Expedientes/Mostrar/<?= $data['personal']['id_empleado']; ?>'">
                                Ver Expedientes
                            </button>
                        <?php } else { ?>
                            <button type="button" class="btn text-white btn-danger" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Ver Expedientes
                            </button>
                        <?php } ?>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-black mb-4">
                    <div class="card-body">Informacion Academica</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">

                        <?php if (!empty($_SESSION['permisos'][6]['leer'])) { ?>
                            <button class="btn text-black btn-warning" onclick="window.location.href='<?= base_url(); ?>/Info/Academica/<?= $data['personal']['identificacion']; ?>'">
                            Informacion Academica
                            </button>
                        <?php } else { ?>
                            <button type="button" class="btn text-black btn-warning" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Informacion Academica
                            </button>
                        <?php } ?>

                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <input type="hidden" id="role_id" value="<?= $_SESSION['PersonalData']['role_id']; ?>">

    <?php footerAdmin($data); ?>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <!-- Centrado verticalmente -->
        <div class="modal-content border-0 shadow-lg"> <!-- Sombra y sin bordes -->
            <div class="modal-header bg-danger text-white"> <!-- Fondo rojo para alerta -->
                <h1 class="modal-title fs-5 d-flex align-items-center gap-2"> <!-- Flex para alinear ícono -->
                    <i class="fas fa-exclamation-circle"></i> <!-- Ícono de advertencia -->
                    <span>Acceso Denegado</span>
                </h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 text-center"> <!-- Padding vertical y centrado -->
                <div class="mb-3"> <!-- Margen inferior -->
                    <i class="fas fa-lock fa-3x text-danger mb-3"></i> <!-- Ícono grande -->
                </div>
                <h5 class="fw-bold mb-2">¡No tienes permisos!</h5> <!-- Texto en negrita -->
                <p class="text-muted">No cuentas con los permisos necesarios para acceder a esta vista.</p>
            </div>
            <div class="modal-footer justify-content-center border-0"> <!-- Centrado y sin bordes -->
                <button type="button" class="btn btn-dark px-4 rounded-pill" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let role_id = <?= json_encode($_SESSION['role_id'] ?? 0); ?>;
    let permisos = <?= json_encode($_SESSION['permisos'] ?? []); ?>;
</script>

<script src="<?= base_url(); ?>/Assets/js/scripts.js"></script>