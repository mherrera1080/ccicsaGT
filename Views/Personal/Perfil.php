<?php headerAdmin($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/info.css" />
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/perfil.css" />

<div class="main p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="card mb-4 text-center">
                    <div class="card-body position-relative">
                        <div class="profile-img-container">
                            <div class="profile-img-container">
                                <?php
                                $imgPath = base_url() . "/Assets/images/Perfiles/" . $data['perfil']['identificacion'];
                                $defaultImg = base_url() . "/Assets/images/Perfiles/usuario.png";
                                if (file_exists("Assets/images/Perfiles/" . $data['perfil']['identificacion'] . ".png")) {
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
                                    data-bs-target="#upIMG" data-id="<?= $data['perfil']['id_empleado']; ?>">
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
                                        $imgPath = base_url() . "/Assets/images/Perfiles/" . $data['perfil']['identificacion'];
                                        $defaultImg = base_url() . "/Assets/images/Perfiles/usuario.png";

                                        if (file_exists("Assets/images/Perfiles/" . $data['perfil']['identificacion'] . ".png")) {
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
                        <i class="fas fa-id-card"></i> Información Laboral del Empleado
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Nombre Completo:</strong> <?= $data['perfil']['nombre_completo']; ?></p>
                                <p><strong>Identificación:</strong> <?= $data['perfil']['identificacion']; ?></p>
                                <p><strong>Código del Empleado:</strong> <?= $data['perfil']['codigo_empleado']; ?></p>
                                <p><strong>Correo Empresarial:</strong> <?= $data['perfil']['correo_empresarial']; ?>
                                </p>
                                <p><strong>Fecha de Ingreso:</strong> <?= $data['perfil']['fecha_ingreso']; ?></p>
                                <p><strong>Fecha Fin. Prueba:</strong> <?= $data['perfil']['fecha_prueba']; ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Puesto Contrato:</strong> <?= $data['perfil']['puesto_contrato_nombre']; ?>
                                </p>
                                <p><strong>Puesto Operativo:</strong> <?= $data['perfil']['puesto_operativo_nombre']; ?>
                                </p>
                                <p><strong>Lider de Proceso:</strong> <?= $data['perfil']['lider_proceso']; ?></p>
                                <p><strong>Jefe Inmediato:</strong> <?= $data['perfil']['jefe_inmediato']; ?></p>
                                <p><strong>Departamento:</strong> <?= $data['perfil']['nombre_departamento']; ?></p>
                                <p><strong>Linea de Negocio:</strong> <?= $data['perfil']['codigo_linea_negocio']; ?>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Cal. Evaluacion de Competencia:</strong>
                                    <?= $data['perfil']['evaluacion_competencia']; ?></p>
                                <p><strong>Fecha Evaluacion de Competencia:</strong>
                                    <?= $data['perfil']['fecha_evaluacion_competencia']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Información de Trabajo -->
        <div class="card mb-4">
            <div class="card-header info-section text-white">
                <i class="fas fa-briefcase"></i> Informa ción Personal del Empleado
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <p><strong>Estado Civil:</strong> <?= $data['info']['estado_civil']; ?></p>
                        <p><strong>Genero:</strong> <?= $data['info']['genero']; ?></p>
                        <p><strong>Edad:</strong> <?= $data['info']['edad']; ?></p>
                        <p><strong>Mes Cumpleaños:</strong> <?= $data['info']['mes_cumpleaños']; ?></p>
                        <p><strong>Fecha Nacimiento:</strong> <?= $data['info']['fecha_nacimiento']; ?></p>
                        <p><strong>Cel. Personal:</strong> <?= $data['info']['numero_cel_personal']; ?></p>
                        <p><strong>Cel. Corporativo:</strong> <?= $data['info']['numero_cel_corporativo']; ?></p>
                        <p><strong>Correo Personal:</strong> <?= $data['info']['correo_electronico_personal']; ?></p>
                    </div>
                    <div class="col-md-2">
                        <p><strong>Cel. Contacto de Emergencia:</strong> <?= $data['info']['numero_cel_emergencia']; ?>
                        </p>
                        <p><strong>Nombre Contacto de Emergencia:</strong>
                            <?= $data['info']['nombre_contacto_emergencia']; ?></p>
                        <p><strong>Parentesco:</strong> <?= $data['info']['parentesco_contacto_emergencia']; ?></p>
                        <p><strong>Cantidad Hijos:</strong> <?= $data['info']['cant_hijos']; ?></p>
                        <p><strong>Tipo de Sangre:</strong> <?= $data['info']['tipo_sangre']; ?></p>
                    </div>
                    <div class="col-md-1">

                    </div>
                    <div class="col-md-3">
                        <p><strong>Direccion Domicilio:</strong> <?= $data['info']['direccion_domicilio']; ?></p>
                        <p><strong>Pais:</strong> <?= $data['info']['pais']; ?></p>
                        <p><strong>Departamento:</strong> <?= $data['info']['departamento_info']; ?></p>
                        <p><strong>Municipio:</strong> <?= $data['info']['municipio']; ?></p>
                        <p><strong>Lugar de Nacimiento:</strong> <?= $data['info']['lugar_nacimiento']; ?></p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>IGGS:</strong> <?= $data['info']['no_seguro_social']; ?></p>
                        <p><strong>No. Identificación Tributaria:</strong>
                            <?= $data['info']['no_identificacion_tributaria']; ?></p>
                        <p><strong>Pasaporte:</strong> <?= $data['info']['no_identificacion_tributaria']; ?></p>
                        <p><strong>Tipo de Identificación:</strong> <?= $data['info']['tipo_identificacion']; ?></p>
                        <p><strong>Vigencia Licencia de Conducir:</strong>
                            <?= $data['info']['vig_licencia_conducir']; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <d  iv class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header info-section text-white">
                        <i class="fas fa-file-alt"></i> Documentos
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <?php foreach ($data['documentos'] as $index => $documento): ?>
                                    <?php if ($index % 2 === 0): ?> <!-- Documentos para la primera columna -->
                                        <div class="document-item mb-3 p-2">
                                            <p class="mb-1">
                                                <strong><?= $documento['nombre_documento']; ?></strong>
                                            </p>
                                            <p class="text-muted">
                                                <?= $documento['ubicacion'] ? 'Archivo guardado' : 'Sin añadir'; ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <div class="col-md-6">
                                <?php foreach ($data['documentos'] as $index => $documento): ?>
                                    <?php if ($index % 2 !== 0): ?> <!-- Documentos para la segunda columna -->
                                        <div class="document-item mb-3 p-2">
                                            <p class="mb-1">
                                                <strong><?= $documento['nombre_documento']; ?></strong>
                                            </p>
                                            <p class="text-muted">
                                                <?= $documento['ubicacion'] ? 'Archivo guardado' : 'Sin añadir'; ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </d>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header info-section text-white">
                        <i class="fas fa-file-alt"></i> Documentos
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <?php foreach ($data['academica'] as $index => $academica): ?>
                                    <?php if ($index % 2 === 0): ?> <!-- Documentos para la primera columna -->
                                        <div class="document-item mb-3 p-2">
                                            <p class="mb-1">
                                                <strong><?= $academica['nombre']; ?></strong>
                                            </p>
                                            <p class="text-muted">
                                                <?= $academica['ubicacion'] ? 'Archivo guardado' : 'Sin añadir'; ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <div class="col-md-6">
                                <?php foreach ($data['academica'] as $index => $academica): ?>
                                    <?php if ($index % 2 !== 0): ?> <!-- Documentos para la segunda columna -->
                                        <div class="document-item mb-3 p-2">
                                            <p class="mb-1">
                                                <strong><?= $academica['nombre']; ?></strong>
                                            </p>
                                            <p class="text-muted">
                                                <?= $academica['ubicacion'] ? 'Archivo guardado' : 'Sin añadir'; ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php footerAdmin($data); ?>

<script src="<?= base_url(); ?>/Assets/js/scripts.js"></script>