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
                                <button type="button" class="btn btn-dark edit-btn" data-bs-toggle="modal"
                                    data-bs-target="#upIMG" data-id="<?= $data['personal']['id_empleado']; ?>">
                                    <i class="fas fa-solid fa-upload"></i>
                                </button>
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
                                    value="<?= $data['personal']['fecha_ingreso'] ?? ''; ?>" disabled>
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

        <form id="editPersonal">
            <input type="hidden" id="id_empleado" name="id_empleado" value="<?= $data['personal']['id_empleado']; ?>">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header info-section text-white">
                            <i class="fas fa-solid fa-helmet-safety"></i> Información Laboral del Empleado
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="puesto_contrato" class="form-label">Puesto Contrato</label>
                                    <select class="form-control selectpicker" data-live-search="true"
                                        id="puesto_contrato" name="puesto_contrato" title="Seleccione">
                                        <option value="<?= $data['personal']['id_puesto_contrato']; ?>" selected>
                                            <?= $data['personal']['puesto_contrato_nombre']; ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="puesto_operativo" class="form-label">Puesto Operativo</label>
                                    <select class="form-control selectpicker" data-live-search="true"
                                        id="puesto_operativo" name="puesto_operativo" title="Seleccione">
                                        <option selected
                                            value="<?= $data['personal']['id_puesto_operativo'] ?? 'N/A'; ?>">
                                            <?= $data['personal']['puesto_operativo_nombre']; ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="jefe_inmediato" class="form-label">Jefe Inmediato</label>
                                    <select class="form-control selectpicker" data-live-search="true"
                                        id="jefe_inmediato" name="jefe_inmediato" title="Seleccione" required>
                                        <option selected value="<?= $data['jefes']['jefe_id'] ?? 'N/A'; ?>">
                                            <?= $data['jefes']['jefe_nombre']; ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="lider_proceso" class="form-label">Lider de Proceso</label>
                                    <select class="form-control selectpicker" data-live-search="true" id="lider_proceso"
                                        name="lider_proceso" title="Seleccione" required>
                                        <option selected value="<?= $data['jefes']['lider_id'] ?? 'N/A'; ?>">
                                            <?= $data['jefes']['lider_nombre']; ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="departamento" class="form-label">Departamento</label>
                                    <select class="form-control selectpicker" data-live-search="true" id="departamento"
                                        name="departamento" title="Seleccione">
                                        <option selected value="<?= $data['personal']['id_departamento'] ?? 'N/A'; ?>">
                                            <?= $data['personal']['nombre_departamento']; ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="area_laboral" class="form-label">Area Laboral | LN</label>
                                    <select class="form-control selectpicker" data-live-search="true" id="area_laboral"
                                        name="area" title="Seleccione">
                                        <option selected value="<?= $data['personal']['id_area'] ?? 'N/A'; ?>">
                                            <?= $data['personal']['codigo_linea_negocio']; ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="correo_empresarial" class="form-label">Correo Corporativo</label>
                                    <input type="text" class="form-control" id="correo_empresarial"
                                        name="correo_empresarial"
                                        value="<?= $data['personal']['correo_empresarial'] ?? 'N/A'; ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="IVR" class="form-label"> IVR </label>
                                    <input type="number" class="form-control" id="IVR" name="IVR"
                                        value="<?= $data['personal']['IVR'] ?? 'N/A'; ?>">
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
                                    <label for="evaluacion_competencia" class="form-label">Cal. Ultima Evaluacion de
                                        Competentia</label>
                                    <input type="number" class="form-control" id="evaluacion_competencia"
                                        name="evaluacion_competencia"
                                        value="<?= $data['personal']['evaluacion_competencia'] ?? 'N/A'; ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="fecha_evaluacion_competencia" class="form-label">Fecha Ultima
                                        Evaluacion</label>
                                    <input type="date" class="form-control" id="fecha_evaluacion_competencia"
                                        name="fecha_evaluacion_competencia"
                                        value="<?= $data['personal']['fecha_evaluacion_competencia'] ?? 'N/A'; ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="" class="form-label">SGN</label>
                                    <input type="file" class="form-control" id="archivo_evalu_competencia"
                                        name="archivo_evalu_competencia"
                                        value="<?= $data['personal']['archivo_evalu_competencia'] ?? 'No se ha subido archivo'; ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="formulario_vacaciones" class="form-label">Formulario Vacaciones</label>
                                    <select class="form-control" id="formulario_vacaciones"
                                        name="formulario_vacaciones">
                                        <option value="Operativa" <?= (isset($data['personal']['formulario_vacaciones']) && $data['personal']['formulario_vacaciones'] === 'Operativa') ? 'selected' : ''; ?>>
                                            Operativa
                                        </option>
                                        <option value="Administrativo"
                                            <?= (isset($data['personal']['formulario_vacaciones']) && $data['personal']['formulario_vacaciones'] === 'Administrativo') ? 'selected' : ''; ?>>
                                            Administrativo
                                        </option>
                                    </select>
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
                                    <label for="salario_base" class="form-label">Salario Base</label>
                                    <input type="number" class="form-control" id="salario_base" name="salario_base"
                                        value="<?= $data['personal']['salario_base'] ?? 'N/A'; ?>"
                                        min="<?= $data['personal']['salario_base'] ?? '0'; ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="bonificacion" class="form-label">Bonificacion</label>
                                    <input type="number" class="form-control" id="bonificacion" name="bonificacion"
                                        value="<?= $data['personal']['bonificacion'] ?? 'N/A'; ?>"
                                        min="<?= $data['personal']['bonificacion'] ?? '0'; ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="kpi1" class="form-label">KPI 1</label>
                                    <input type="number" class="form-control" id="kpi1" name="kpi1"
                                        value="<?= $data['personal']['kpi1'] ?? 'N/A'; ?>"
                                        min="<?= $data['personal']['kpi1'] ?? '0'; ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="kpi2" class="form-label">KPI 2</label>
                                    <input type="number" class="form-control" id="kpi2" name="kpi2"
                                        value="<?= $data['personal']['kpi2'] ?? 'N/A'; ?>"
                                        min="<?= $data['personal']['kpi2'] ?? '0'; ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="kpi_max" class="form-label">KPI Bono Max.</label>
                                    <input type="number" class="form-control" id="kpi_max" name="kpi_max"
                                        value="<?= $data['personal']['kpi_max'] ?? 'N/A'; ?>"
                                        min="<?= $data['personal']['kpi_max'] ?? '0'; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="modal-footer">
                <a class="btn btn-primary btn-lg me-2"
                    href="<?= base_url(); ?>/Personal/Info/<?= $data['personal']['id_empleado']; ?>">
                    <i class="fas fa-edit"></i> regresar
                </a>
                <button type="submit" class="btn btn-primary btn-lg me-2">Guardar cambios</button>
            </div>

        </form>

    </div>
    <?php footerAdmin($data); ?>
</div>

<script src="<?= base_url(); ?>/Assets/js/scripts.js"></script>