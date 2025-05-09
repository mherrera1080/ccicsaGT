<?php headerAdmin($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/info.css" />

<div class="main p-3">
    <div class="container-fluid">
        <form id="addUserForm">
            <!-- Campo oculto para el ID del usuario -->
            <input type="hidden" id="identificacion" name="identificacion"
                value="<?= $data['info']['identificacion']; ?>">
            <input type="hidden" id="info_academica" name="info_academica"
                value="<?= $data['info']['info_academica']; ?>">

            <div class="info-section">
                <h2>Datos Personales de <?= $data['info']['nombres']; ?> </h2>
            </div>
            <div class="info-container">
                <div class="info-item">
                    <label for="mes_cumpleaños" class="form-label">Mes de Cumpleaños</label>
                    <input type="number" class="form-control" id="mes_cumpleaños" name="mes_cumpleaños"
                        value="<?= $data['info']['mes_cumpleaños']; ?>" disabled>
                </div>
                <div class="info-item">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                        value="<?= $data['info']['fecha_nacimiento']; ?>" disabled>
                </div>
                <div class="info-item">
                    <label for="genero" class="form-label">Genero</label>
                    <input type="text" class="form-control" id="genero" name="genero"
                        value="<?= $data['info']['genero']; ?>" disabled>
                </div>
                <div class="info-item">
                    <label for="estado_civil" class="form-label">Estado Civil</label>
                    <input type="text" class="form-control" id="estado_civil" name="estado_civil"
                        value="<?= $data['info']['estado_civil']; ?>" disabled>
                </div>
                <div class="info-item">
                    <label for="Pais" class="form-label">País</label>
                    <input type="text" class="form-control" id="Pais" name="Pais" value="<?= $data['info']['Pais']; ?>"
                        disabled>
                </div>
                <div class="info-item">
                    <label for="departamento" class="form-label">Departamento</label>
                    <input type="text" class="form-control" id="departamento" name="departamento"
                        value="<?= $data['info']['departamento']; ?>" disabled>
                </div>
                <div class="info-item">
                    <label for="municipio" class="form-label">Municipio</label>
                    <input type="text" class="form-control" id="municipio" name="municipio"
                        value="<?= $data['info']['municipio']; ?>" disabled>
                </div>
            </div>

            <div class="info-container">
                <div class="info-item">
                    <label for="edad" class="form-label">Edad</label>
                    <input type="number" class="form-control" id="edad" name="edad"
                        value="<?= $data['info']['edad']; ?>" disabled>
                </div>
                <div class="info-item">
                    <label for="tipo_identificacion" class="form-label">Tipo de Identificación</label>
                    <input type="text" class="form-control" id="tipo_identificacion" name="tipo_identificacion"
                        value="<?= $data['info']['tipo_identificacion']; ?>" disabled>
                </div>
                <div class="info-item">
                    <label for="pasaporte" class="form-label">Pasaporte</label>
                    <input type="text" class="form-control" id="pasaporte" name="pasaporte"
                        value="<?= $data['info']['pasaporte']; ?>" disabled>
                </div>
                <div class="info-item">
                    <label for="lugar_nacimiento" class="form-label">Lugar de Nacimiento</label>
                    <input type="text" class="form-control" id="lugar_nacimiento" name="lugar_nacimiento"
                        value="<?= $data['info']['lugar_nacimiento']; ?>" disabled>
                </div>
                <div class="info-item">
                    <label for="no_seguro_social" class="form-label">No. Seguro Social</label>
                    <input type="text" class="form-control" id="no_seguro_social" name="no_seguro_social"
                        value="<?= $data['info']['no_seguro_social']; ?>" disabled>
                </div>
                <div class="info-item">
                    <label for="no_identificacion_tributaria" class="form-label">N.I.T</label>
                    <input type="number" class="form-control" id="no_identificacion_tributaria"
                        name="no_identificacion_tributaria"
                        value="<?= $data['info']['no_identificacion_tributaria']; ?>" disabled>
                </div>
                <div class="info-item">
                    <label for="vig_licencia_conducir" class="form-label">Exp. Licencia de Conducir</label>
                    <input type="date" class="form-control" id="vig_licencia_conducir" name="vig_licencia_conducir"
                        value="<?= $data['info']['vig_licencia_conducir']; ?>" disabled>
                </div>
            </div>

            <div class="info-container">
                <div class="info-item">
                    <label for="estudios" class="form-label">Estudios</label>
                    <input type="estudios" class="form-control" id="estudios" name="estudios"
                        value="<?= $data['info']['estudios']; ?>" disabled>
                </div>
                <div class="info-item">
                    <label for="nivel_educativo" class="form-label">Nivel Educativo</label>
                    <input type="nivel_educativo" class="form-control" id="nivel_educativo" name="nivel_educativo"
                        value="<?= $data['info']['nivel_educativo']; ?>" disabled>
                </div>
                <div class="info-item">
                </div>
                <div class="info-item">
                </div>
                <div class="info-item">
                </div>
                <div class="info-item">
                </div>
                <div class="info-item">
                </div>
            </div>



            <div class="info-section">
                <h2>Informacion Familiares</h2>
            </div>
            <div class="info-container">
                <div class="info-item">
                    <label for="numero_cel_corporativo" class="form-label">Número Celular Corporativo</label>
                    <input type="text" class="form-control" id="numero_cel_corporativo" name="numero_cel_corporativo"
                        value="<?= $data['info']['numero_cel_corporativo']; ?>" disabled>
                </div>
                <div class="info-item">
                    <label for="numero_cel_personal" class="form-label">Número Celular Personal</label>
                    <input type="text" class="form-control" id="numero_cel_personal" name="numero_cel_personal"
                        value="<?= $data['info']['numero_cel_personal']; ?>" disabled>
                </div>
                <div class="info-item">
                    <label for="numero_cel_emergencia" class="form-label">Número Celular de Emergencia</label>
                    <input type="text" class="form-control" id="numero_cel_emergencia" name="numero_cel_emergencia"
                        value="<?= $data['info']['numero_cel_emergencia']; ?>" disabled>
                </div>
                <div class="info-item">
                    <label for="nombre_contacto_emergencia" class="form-label">Nombre del Contacto de Emergencia</label>
                    <input type="text" class="form-control" id="nombre_contacto_emergencia"
                        name="nombre_contacto_emergencia" value="<?= $data['info']['nombre_contacto_emergencia']; ?>"
                        disabled>
                </div>
                <div class="info-item">
                    <label for="parentesco_contacto_emergencia" class="form-label">Parentesco con el Contacto</label>
                    <input type="text" class="form-control" id="parentesco_contacto_emergencia"
                        name="parentesco_contacto_emergencia"
                        value="<?= $data['info']['parentesco_contacto_emergencia']; ?>" disabled>
                </div>
            </div>

            <div class="info-container">
                <div class="info-item">
                    <label for="direccion_domicilio" class="form-label">Dirección Domicilio</label>
                    <input type="text" class="form-control" id="direccion_domicilio" name="direccion_domicilio"
                        value="<?= $data['info']['direccion_domicilio']; ?>" disabled>
                </div>
                <div class="info-item">
                    <label for="correo_electronico_personal" class="form-label">Correo Electrónico Personal</label>
                    <input type="email" class="form-control" id="correo_electronico_personal"
                        name="correo_electronico_personal" value="<?= $data['info']['correo_electronico_personal']; ?>"
                        disabled>
                </div>
                <div class="info-item">
                    <label for="cant_hijos" class="form-label">Cantidad de Hijos</label>
                    <input type="number" class="form-control" id="cant_hijos" name="cant_hijos"
                        value="<?= $data['info']['cant_hijos']; ?>" disabled>
                </div>
                <div class="info-item">
                    <label for="tipo_sangre" class="form-label">Tipo de Sangre</label>
                    <input type="text" class="form-control" id="tipo_sangre" name="tipo_sangre"
                        value="<?= $data['info']['tipo_sangre']; ?>" disabled>
                </div>
                <div class="info-item">
                </div>
            </div>

            <div class="modal-footer">

                <?php if (!empty($_SESSION['permisos'][2]['editar'])) { ?>
                    <button class="btn text-black btn-warning"
                        onclick="window.location.href='<?= base_url(); ?>/Info/Editar/<?= $data['info']['identificacion']; ?>'">
                        Editar Empleado
                    </button>
                <?php } else { ?>
                    <button type="button" class="btn text-black btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Editar Empleado
                    </button>
                <?php } ?>

            </div>
        </form>
    </div>
    <?php footerAdmin($data); ?>
</div>

<script src="<?= base_url(); ?>/Assets/js/scripts.js"></script>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <!-- Centrado verticalmente -->
        <div class="modal-content border-0 shadow-lg"> <!-- Sombra y sin bordes -->
            <div class="modal-header bg-danger text-white"> <!-- Fondo rojo para alerta -->
                <h1 class="modal-title fs-5 d-flex align-items-center gap-2"> <!-- Flex para alinear ícono -->
                    <i class="fas fa-exclamation-circle"></i> <!-- Ícono de advertencia -->
                    <span>Acceso Denegado</span>
                </h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
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