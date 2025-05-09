<?php headerAdmin($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/info.css" />
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/perfil.css" />


<div class="main p-3">
    <div class="container-fluid">
        <div class="row">
            <!-- ESPACIADOR -->
            <div class="col-md-3">
                <div class="card mb-4 text-center">
                    <div class="card-body position-relative">
                        <div class="profile-img-container">
                            <div class="profile-img-container">
                                <?php
                                $imgPath = base_url() . "/Assets/images/Perfiles/" . $data['personal']['identificacion'];
                                $defaultImg = base_url() . "/Assets/images/Perfiles/usuario.png";
                                if (file_exists("Assets/images/Perfiles/" . $data['personal']['identificacion'] . ".png")) {
                                    // Mostrar imagen personalizada si existe
                                    echo '<img src="' . $imgPath . '.png" alt="Foto de Perfil" class="img-thumbnail profile-img">';
                                } else {
                                    // Mostrar imagen predeterminada
                                    echo '<img src="' . $defaultImg . '" alt="Foto de Perfil" class="img-thumbnail profile-img">';
                                }
                                ?>
                            </div>
                            <div class="overlay-buttons">
                                <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                    data-bs-target="#verIMG">
                                    <i class="fas fa-regular fa-eye"></i>
                                </button>
                                <!-- <button type="button" class="btn btn-dark edit-btn" data-bs-toggle="modal"
                                    data-bs-target="#upIMG" data-id="<?= $data['personal']['id_empleado']; ?>">
                                    <i class="fas fa-solid fa-upload"></i>
                                </button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="verIMG" tabindex="-1" aria-labelledby="verIMGLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="verIMGLabel">Imagen de Perfil</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="col-md-15">
                                <div class="card mb-4 text-center">
                                    <div class="profile-img-container">
                                        <?php
                                        $imgPath = base_url() . "/Assets/images/Perfiles/" . $data['personal']['identificacion'];
                                        $defaultImg = base_url() . "/Assets/images/Perfiles/usuario.png";

                                        if (file_exists("Assets/images/Perfiles/" . $data['personal']['identificacion'] . ".png")) {
                                            // Mostrar imagen personalizada si existe
                                            echo '<img src="' . $imgPath . '.png" alt="Foto de Perfil" class="img-thumbnail perfil-img">';
                                        } else {
                                            // Mostrar imagen predeterminada
                                            echo '<img src="' . $defaultImg . '" alt="Foto de Perfil" class="img-thumbnail perfil-img">';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="upIMG" tabindex="-1" aria-labelledby="upIMGLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="upIMGLabel">Subir Imagen de Perfil</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="uploadForm" action="ruta_para_subir_imagen.php" method="post"
                                enctype="multipart/form-data">
                                <input type="hidden" id="id_empleado" name="id_empleado">
                                <div id="dropArea" class="drop-area text-center">
                                    <p>Arrastra y suelta una imagen aquí o haz clic para seleccionar</p>
                                    <input type="file" id="fileInput" name="file" hidden>
                                    <img id="preview" src="" alt="Vista previa" class="img-thumbnail mt-3"
                                        style="display: none;">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" form="uploadForm" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Básica del Empleado -->
            <div class="col-md-9">
                <div class="card mb-4">
                    <div class="card-header info-section text-white">
                        <i class="fas fa-id-card"></i> Información Base del Empleado
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="codigo_empleado" class="form-label">Código Empleado</label>
                                <input type="text" class="form-control" id="codigo_empleado" name="codigo_empleado"
                                    value="<?= $data['personal']['codigo_empleado'] ?? 'N/A'; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="nombres" class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="nombres" name="nombres"
                                    value="<?= $data['personal']['nombres'] ?? 'N/A'; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos"
                                    value="<?= $data['personal']['apellidos'] ?? 'N/A'; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="identificacion" class="form-label">No. Identificación</label>
                                <input type="text" class="form-control" id="identificacion" name="identificacion"
                                    value="<?= $data['personal']['identificacion'] ?? 'N/A'; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="fecha_ingreso" class="form-label">Fecha Ingreso</label>
                                <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso"
                                    value="<?= $data['personal']['fecha_ingreso'] ?? 'N/A'; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="fecha_ingreso" class="form-label">Fecha Contratacion</label>
                                <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso"
                                    value="<?= $data['personal']['fecha_contratacion'] ?? 'N/A'; ?>" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!--SEGUNDA FILA -->

        <div class="row">

            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header info-section text-white">
                        <i class="fas fa-solid fa-helmet-safety"></i> Información Laboral del Empleado
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="codigo_empleado" class="form-label">Puesto Contrato</label>
                                <input type="text" class="form-control"
                                    value="<?= $data['personal']['puesto_contrato_nombre'] ?? 'N/A'; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="nombres" class="form-label">Puesto Operativo</label>
                                <input type="text" class="form-control" id="nombres" name="nombres"
                                    value="<?= $data['personal']['puesto_operativo_nombre'] ?? 'N/A'; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="apellidos" class="form-label">Lider de Proceso</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos"
                                    value="<?= $data['jefes']['lider_nombre'] ?? 'N/A'; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="identificacion" class="form-label">Jefe Inmediato</label>
                                <input type="text" class="form-control" id="identificacion" name="identificacion"
                                    value="<?= $data['jefes']['jefe_nombre'] ?? 'N/A'; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="nombres" class="form-label">Departamento / Area</label>
                                <input type="text" class="form-control" id="nombres" name="nombres"
                                    value="<?= $data['personal']['nombre_departamento'] ?? 'N/A'; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="nombres" class="form-label">Linea de Negocio</label>
                                <input type="text" class="form-control" id="nombres" name="nombres"
                                    value="<?= $data['personal']['codigo_linea_negocio'] ?? 'N/A'; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="nombres" class="form-label">Correo Corporativo</label>
                                <input type="text" class="form-control" id="nombres" name="nombres"
                                    value="<?= $data['personal']['correo_empresarial'] ?? 'N/A'; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="IVR" class="form-label">IVR</label>
                                <input type="number" class="form-control" id="IVR" name="IVR"
                                    value="<?= $data['personal']['IVR'] ?? 'N/A'; ?>" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">

            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header info-section text-white">
                        <i class="fas fa-solid fa-helmet-safety"></i> Información Laboral extra
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="nombres" class="form-label">Cal. Ultima Evaluacion de Competentia</label>
                                <input type="text" class="form-control" id="nombres" name="nombres"
                                    value="<?= $data['personal']['evaluacion_competencia'] ?? 'N/A'; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="apellidos" class="form-label">Fecha Ultima Evaluacion</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos"
                                    value="<?= $data['personal']['fecha_evaluacion_competencia'] ?? 'N/A'; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="archivo_evalu_competencia" class="form-label">Evaluacion de
                                    Competencia</label>
                                <?php
                                $filePath = "Expedientes/SGN/" . $data['personal']['identificacion'] . ".pdf";
                                $fileUrl = base_url() . "/" . $filePath;

                                if (file_exists($filePath)) {
                                    // Mostrar enlace para descargar o visualizar el PDF
                                    echo '<a href="' . $fileUrl . '" target="_blank" class="btn form-control btn-primary">Ver Documento</a>';
                                } else {
                                    // Mostrar input para subir archivo si no existe
                                    echo '<button type="file" class="form-control" id="archivo_evalu_competencia" name="archivo_evalu_competencia">';
                                    echo '<small class="text-danger">No se ha subido ningún archivo.</small>';
                                }
                                ?>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="formulario_vacaciones" class="form-label">Formulario Vacaciones</label>
                                <input type="text" class="form-control" id="formulario_vacaciones"
                                    name="formulario_vacaciones"
                                    value="<?= $data['personal']['formulario_vacaciones'] ?? 'N/A'; ?>" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header info-section text-white">
                        <i class="fas fa-solid fa-helmet-safety"></i> Información Sueldo
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="nombres" class="form-label">Salario Base</label>
                                <input type="text" class="form-control" id="nombres" name="nombres"
                                    value="<?= $data['personal']['salario_base'] ?? 'N/A'; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="nombres" class="form-label">Bonificacion</label>
                                <input type="text" class="form-control" id="nombres" name="nombres"
                                    value="<?= $data['personal']['bonificacion'] ?? 'N/A'; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="nombres" class="form-label">KPI 1</label>
                                <input type="text" class="form-control" id="nombres" name="nombres"
                                    value="<?= $data['personal']['kpi1'] ?? 'N/A'; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="nombres" class="form-label">KPI 2</label>
                                <input type="text" class="form-control" id="nombres" name="nombres"
                                    value="<?= $data['personal']['kpi2'] ?? 'N/A'; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="nombres" class="form-label">KPI Bono Max.</label>
                                <input type="text" class="form-control" id="nombres" name="nombres"
                                    value="<?= $data['personal']['kpi_max'] ?? 'N/A'; ?>" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php if (!empty($_SESSION['permisos'][3]['editar'])) { ?>
            <div class="modal-footer">
                <a class="btn btn-primary btn-lg me-2"
                    href="<?= base_url(); ?>/Personal/Editar/<?= $data['personal']['id_empleado']; ?>">
                    <i class="fas fa-edit"></i> Editar Empleado
                </a>
            </div>
        <?php } ?>

    </div>
    <?php footerAdmin($data); ?>
</div>

<script src="<?= base_url(); ?>/Assets/js/scripts.js"></script>