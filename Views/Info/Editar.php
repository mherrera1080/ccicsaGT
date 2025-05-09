<?php headerAdmin($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/info.css" />

<div class="main p-3">
    <div class="container-fluid">
        <form id="addUserForm">
            <!-- Campo oculto para el ID del usuario -->
            <input type="hidden" id="identificacion" name="identificacion"
                value="<?= htmlspecialchars($data['info']['identificacion']); ?>">

            <div class="info-section">
                <h2>Datos Personales</h2>
            </div>

            <div class="info-container">
                <div class="info-item">
                    <label for="mes_cumpleaños" class="form-label">Mes de Cumpleaños</label>
                    <input type="number" class="form-control" id="mes_cumpleaños" name="mes_cumpleaños"
                        value="<?= htmlspecialchars($data['info']['mes_cumpleaños']); ?>">
                </div>
                <div class="info-item">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                        value="<?= htmlspecialchars($data['info']['fecha_nacimiento']); ?>">
                </div>
                <div class="info-item">
                    <label for="genero" class="form-label">Género</label>
                    <select class="form-control" id="genero" name="genero">
                        <option value="Hombre" <?= (isset($data['info']['genero']) && $data['info']['genero'] === 'Hombre') ? 'selected' : ''; ?>>Hombre</option>
                        <option value="Mujer" <?= (isset($data['info']['genero']) && $data['info']['genero'] === 'Mujer') ? 'selected' : ''; ?>>Mujer</option>
                    </select>
                </div>
                <div class="info-item">
                    <label for="estado_civil" class="form-label">Estado Civil</label>
                    <input type="text" class="form-control" id="estado_civil" name="estado_civil"
                        value="<?= htmlspecialchars($data['info']['estado_civil']); ?>">
                </div>
                <div class="info-item">
                    <label for="Pais" class="form-label">País</label>
                    <input type="text" class="form-control" id="Pais" name="Pais"
                        value="<?= htmlspecialchars($data['info']['Pais']); ?>">
                </div>
                <div class="info-item">
                    <label for="departamento" class="form-label">Departamento</label>
                    <input type="text" class="form-control" id="departamento" name="departamento"
                        value="<?= htmlspecialchars($data['info']['departamento']); ?>">
                </div>
                <div class="info-item">
                    <label for="municipio" class="form-label">Municipio</label>
                    <input type="text" class="form-control" id="municipio" name="municipio"
                        value="<?= htmlspecialchars($data['info']['municipio']); ?>">
                </div>
            </div>

            <div class="info-container">
                <div class="info-item">
                    <label for="edad" class="form-label">Edad</label>
                    <input type="number" class="form-control" id="edad" name="edad"
                        value="<?= htmlspecialchars($data['info']['edad']); ?>">
                </div>
                <div class="info-item">
                    <label for="tipo_identificacion" class="form-label">Tipo de Identificación</label>
                    <input type="text" class="form-control" id="tipo_identificacion" name="tipo_identificacion"
                        value="<?= htmlspecialchars($data['info']['tipo_identificacion']); ?>">
                </div>
                <div class="info-item">
                    <label for="pasaporte" class="form-label">Pasaporte</label>
                    <input type="text" class="form-control" id="pasaporte" name="pasaporte"
                        value="<?= htmlspecialchars($data['info']['pasaporte']); ?>">
                </div>
                <div class="info-item">
                    <label for="lugar_nacimiento" class="form-label">Lugar de Nacimiento</label>
                    <input type="text" class="form-control" id="lugar_nacimiento" name="lugar_nacimiento"
                        value="<?= htmlspecialchars($data['info']['lugar_nacimiento']); ?>">
                </div>
                <div class="info-item">
                    <label for="no_seguro_social" class="form-label">No. Seguro Social</label>
                    <input type="text" class="form-control" id="no_afiliacion_iggs" name="no_seguro_social"
                        value="<?= htmlspecialchars($data['info']['no_seguro_social']); ?>">
                </div>
                <div class="info-item">
                    <label for="no_identificacion_tributaria" class="form-label">N.I.T</label>
                    <input type="text" class="form-control" id="no_identificacion_tributaria"
                        name="no_identificacion_tributaria"
                        value="<?= htmlspecialchars($data['info']['no_identificacion_tributaria']); ?>">
                </div>
                <div class="info-item">
                    <label for="vig_licencia_conducir" class="form-label">Exp. Licencia de Conducir</label>
                    <input type="date" class="form-control" id="vig_licencia_conducir" name="vig_licencia_conducir"
                        value="<?= htmlspecialchars($data['info']['vig_licencia_conducir']); ?>">
                </div>
            </div>

            <div class="info-container">
                <div class="info-item">
                    <label for="id_estudios" class="form-label">Estudios</label>
                    <select class="form-control selectpicker" data-live-search="true" id="id_estudios" name="estudios"
                        title="Seleccione" required>
                        <?php if (empty($data['info']['estudios'])): ?>
                            <!-- Si estudios está vacío, muestra una opción predeterminada -->
                            <option selected value="N/A">Seleccione una opción</option>
                        <?php else: ?>
                            <!-- Si estudios tiene un valor, muestra ese valor -->
                            <option selected value="<?= $data['info']['id_categoria_estudios'] ?? 'N/A'; ?>">
                                <?= $data['info']['estudios']; ?>
                            </option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="info-item">
                    <label for="nivel_educativo" class="form-label">Nivel Educativo</label>
                    <select class="form-control" name="nivel_educativo" id="id_nivel_educativo" required>
                        <?php if (empty($data['info']['nivel_educativo'])): ?>
                            <!-- Si nivel_educativo está vacío, muestra una opción predeterminada -->
                            <option selected value="N/A">Seleccione un nivel educativo</option>
                        <?php else: ?>
                            <!-- Si nivel_educativo tiene un valor, muestra ese valor -->
                            <option selected value="<?= $data['info']['id_categoria_educacion'] ?? 'N/A'; ?>">
                                <?= $data['info']['nivel_educativo']; ?>
                            </option>
                        <?php endif; ?>
                    </select>
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
                <h2>Info Familiares</h2>
            </div>
            <div class="info-container">
                <div class="info-item">
                    <label for="numero_cel_corporativo" class="form-label">Número Celular Corporativo</label>
                    <input type="text" class="form-control" id="numero_cel_corporativo" name="numero_cel_corporativo"
                        value="<?= htmlspecialchars($data['info']['numero_cel_corporativo']); ?>">
                </div>
                <div class="info-item">
                    <label for="numero_cel_personal" class="form-label">Número Celular Personal</label>
                    <input type="text" class="form-control" id="numero_cel_personal" name="numero_cel_personal"
                        value="<?= htmlspecialchars($data['info']['numero_cel_personal']); ?>">
                </div>
                <div class="info-item">
                    <label for="numero_cel_emergencia" class="form-label">Número Celular de Emergencia</label>
                    <input type="text" class="form-control" id="numero_cel_emergencia" name="numero_cel_emergencia"
                        value="<?= htmlspecialchars($data['info']['numero_cel_emergencia']); ?>">
                </div>
                <div class="info-item">
                    <label for="nombre_contacto_emergencia" class="form-label">Nombre del Contacto de Emergencia</label>
                    <input type="text" class="form-control" id="nombre_contacto_emergencia"
                        name="nombre_contacto_emergencia"
                        value="<?= htmlspecialchars($data['info']['nombre_contacto_emergencia']); ?>">
                </div>
                <div class="info-item">
                    <label for="parentesco_contacto_emergencia" class="form-label">Parentesco con el Contacto</label>
                    <input type="text" class="form-control" id="parentesco_contacto_emergencia"
                        name="parentesco_contacto_emergencia"
                        value="<?= htmlspecialchars($data['info']['parentesco_contacto_emergencia']); ?>">
                </div>
            </div>

            <div class="info-container">
                <div class="info-item">
                    <label for="direccion_domicilio" class="form-label">Dirección Domicilio</label>
                    <input type="text" class="form-control" id="direccion_domicilio" name="direccion_domicilio"
                        value="<?= htmlspecialchars($data['info']['direccion_domicilio']); ?>">
                </div>
                <div class="info-item">
                    <label for="correo_electronico_personal" class="form-label">Correo Electrónico Personal</label>
                    <input type="email" class="form-control" id="correo_electronico_personal"
                        name="correo_electronico_personal"
                        value="<?= htmlspecialchars($data['info']['correo_electronico_personal']); ?>">
                </div>
                <div class="info-item">
                    <label for="cant_hijos" class="form-label">Cantidad de Hijos</label>
                    <input type="number" class="form-control" id="cant_hijos" name="cant_hijos"
                        value="<?= htmlspecialchars($data['info']['cant_hijos']); ?>">
                </div>
                <div class="info-item">
                    <label for="tipo_sangre" class="form-label">Tipo de Sangre</label>
                    <select class="form-control" id="tipo_sangre" name="tipo_sangre">
                        <option value="A+" <?= (isset($data['info']['tipo_sangre']) && $data['info']['tipo_sangre'] === 'A+') ? 'selected' : ''; ?>>A+</option>
                        <option value="B+" <?= (isset($data['info']['tipo_sangre']) && $data['info']['tipo_sangre'] === 'B+') ? 'selected' : ''; ?>>B+</option>
                        <option value="O+" <?= (isset($data['info']['tipo_sangre']) && $data['info']['tipo_sangre'] === 'O+') ? 'selected' : ''; ?>>O+</option>
                        <option value="AB+" <?= (isset($data['info']['tipo_sangre']) && $data['info']['tipo_sangre'] === 'AB+') ? 'selected' : ''; ?>>AB+</option>
                        <!-- Agrega más opciones según sea necesario -->
                    </select>
                </div>
                <div class="info-item">
                </div>
            </div>

            <div class="modal-footer">
                <a class="btn btn-primary btn-lg me-2"
                    href="<?= base_url(); ?>/Info/Mostrar/<?= $data['info']['identificacion']; ?>">
                    <i class="fas fa-edit"></i> Regresar
                </a>
                <button type="submit" class="btn btn-primary btn-lg me-2">Guardar cambios</button>
            </div>
        </form>
    </div>

    <?php footerAdmin($data); ?>
</div>

<script src="<?= base_url(); ?>/Assets/js/scripts.js"></script>