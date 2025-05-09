<?php headerVacaciones($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/info.css" />

<div class="main p-3">
    <div style="height: 25px;" class="text-center"></div>
    <div class=" container-fluid mt-4">

        <h1>Bienvenido, <?= $_SESSION['userData']['nombres'] . ' ' . $_SESSION['userData']['apellidos']; ?></h1>
        <p><strong>Correo: </strong> <?= $_SESSION['userData']['correo_empresarial']; ?></p>

        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Solicitudes de Vacaciones</h2>
        </div>

        <?php if ($data['usuario']['solicitudes'] === "pendiente") { ?>
            <button type="button" class="btn btn-primary pendiente-btn" data-bs-toggle="modal"
                data-bs-target="#pendienteModal" data-id="<?= $data['usuario']['id_empleado'] ?>">
                Solicitar vacaciones
            </button>
        <?php } ?>

        <div class="modal fade" id="pendienteModal" tabindex="-1" aria-labelledby="pendienteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <!-- Encabezado del modal -->
                    <div class="modal-header bg-danger text-black">
                        <h5 class="modal-title" id="pendienteModalLabel">Mensaje de Alerta</h5>
                        <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <!-- Cuerpo del modal -->
                    <div class="modal-body">
                        <label for="nombre_completo_pendiente" class="form-label">Solicitud en Proceso</label>
                        <table id="tablaFechasPendiente" class="table table-striped table-bordered nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th>No. Solicitud</th>
                                    <th>Fecha Solicitada</th>
                                    <th>Dias</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Filas de fechas dinámicamente añadidas aquí -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($data['usuario']['formulario_vacaciones'] === "Administrativo" && $data['usuario']['solicitudes'] === "no-pendiente") { ?>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#solicitudVacaciones">
                Solicitar vacaciones
            </button>
        <?php } ?>

        <?php if ($data['usuario']['formulario_vacaciones'] === "Operativa" && $data['usuario']['solicitudes'] === "no-pendiente") { ?>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formularioCorporativos">
                Solicitar vacaciones
            </button>
        <?php } ?>

    </div>



    <div style="height: 50px;"></div>
    <!-- Contenido adicional de la vista -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-people-arrows"></i> Solicitud de Vacaciones
                </div>
                <div class="card-body">
                    <input type="hidden" id="id_empleado" value="<?= $data['usuario']['id_empleado']; ?>">
                    <input type="hidden" id="formulario_vacaciones"
                        value="<?= $data['usuario']['formulario_vacaciones']; ?>">
                    <table id="tableSolicitud" class="table table-striped table-bordered nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>No. Solicitud</th>
                                <th>Fecha Solicitud</th>
                                <th>Razon</th>
                                <th>Dias</th>
                                <th>Fecha Revision</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Datos del personal se insertarán aquí -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php footerAdmin($data); ?>
<script src="<?= base_url(); ?>/Assets/js/scripts.js"></script>
</div>

<div class="modal fade" id="infoOperativaModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Encabezado -->
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Solicitud de Vacaciones</h5>
                <button type="button" class="btn btn-light btn-close" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- ID Solicitud oculto -->
                <input type="hidden" name="id_solicitud_info" id="id_solicitud_info">
                <!-- Responsable de Aprobación -->
                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="nombre_completo_info" name="nombre_completo" readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_1" class="form-label">Aprobador 1</label>
                    <input type="text" class="form-control" id="revision_aprobador_1_info" name="revision_aprobador_1"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_2_info" class="form-label">Aprobador 2</label>
                    <input type="text" class="form-control" id="revision_aprobador_2_info"
                        name="revision_aprobador_2_info" readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_3_info" class="form-label">Aprobador 3</label>
                    <input type="text" class="form-control" id="revision_aprobador_3_info"
                        name="revision_aprobador_3_info" readonly>
                </div>
                <div class="mb-3">
                    <label for="asunto" class="form-label">Categoria</label>
                    <input type="text" class="form-control" id="asunto_info" name="asunto" readonly>
                </div>
                <!-- Comentario -->
                <div class="mb-3">
                    <label for="comentario_solicitud_info" class="form-label">Comentario Solicitud</label>
                    <textarea class="form-control" name="comentario_solicitud_info" id="comentario_solicitud_info"
                        rows="3" readonly></textarea>
                </div>
                <!-- Tabla de Fechas -->
                <h6 class="fw-bold mb-3">Fechas Solicitadas</h6>
                <table id="tablaFechasEditInfo" class="table table-bordered ">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Valor</th>
                            <th>Día</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas de fechas dinámicamente añadidas aquí -->
                    </tbody>
                </table>
                <div class="mb-3">
                    <label for="estado_info" class="form-label">Seguimiento</label>
                    <input type="text" class="form-control" id="estado_info" name="estado_info" disabled>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hacer Solicitud-->
<div class="modal fade" id="solicitudVacaciones" tabindex="-1" data-bs-backdrop="static"
    aria-labelledby="solicitudVacacionesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="solicitudVacacionesLabel">Formulario de Solicitud de Vacaciones</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="SolicitudForm">
                <!-- <input type="hidden" id="id_empleado" name="id_empleado"> -->
                <div class="modal-body">
                    <input type="hidden" id="id_empleado" name="id_empleado"
                        value="<?= $_SESSION['userData']['id_empleado']; ?>">
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label for="nombres" class="form-label">Usuario Solicitante</label>
                            <input type="text" class="form-control" id="" name=""
                                value="<?= $data['usuario']['nombres'] . ' ' . $data['usuario']['apellidos']; ?>"
                                disabled>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="identificacion" class="form-label">Correo</label>
                            <input type="text" class="form-control" id="" name=""
                                value="<?= $data['usuario']['correo_empresarial']; ?>" disabled>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="formulario" class="form-label">Formulario</label>
                            <input type="text" class="form-control" id="" name=""
                                value="<?= $data['usuario']['formulario_vacaciones']; ?>" disabled>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="responsable_aprobacion" class="form-label">Aprobador</label>
                        <select class="form-control selectpicker" data-live-search="true" id="responsable_aprobacion"
                            name="responsable_aprobacion" required title="Seleccione una razón">
                            <!-- Opciones aquí -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_categoria" class="form-label">Categoria</label>
                        <select class="form-control selectpicker" data-live-search="true" id="id_categoria"
                            name="id_categoria" required title="Seleccione una razón">
                            <!-- Opciones aquí -->
                        </select>
                    </div>
                    <input type="hidden" id="" name="area_solicitud" value="<?= $data['usuario']['area_solicitud']; ?>">
                    <div class="mb-3">
                        <label for="comentario_solicitud" class="form-label">Comentario Solicitud</label>
                        <textarea class="form-control" id="comentario_solicitud" name="comentario_solicitud" rows="3"
                            placeholder="Escribe aquí una descripción detallada..."></textarea>
                    </div>
                    <!-- Tabla para fechas -->
                    <div class="mb-3">
                        <label for="fechas" class="form-label">Fechas Solicitadas</label>
                        <table class="table table-bordered" id="tablaFechas">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Valor (1 o 0.5)</th>
                                    <th>Turno</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Fechas se agregarán dinámicamente aquí -->
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success" id="agregarFecha">Agregar Fecha</button>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary">Confirmar Solicitud
                        <span id="spinerSubmit" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="formularioCorporativos" tabindex="-1" data-bs-backdrop="static"
    aria-labelledby="formularioCorporativosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formularioCorporativosLabel">Formulario de Solicitud de Vacaciones.
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="SolicitudOperativaForm">
                <!-- <input type="hidden" id="id_empleado" name="id_empleado"> -->
                <div class="modal-body">
                    <input type="hidden" id="id_empleado" name="id_empleado"
                        value="<?= $_SESSION['userData']['id_empleado']; ?>">
                    <input type="hidden" id="area_solicitud" name="area_solicitud"
                        value="<?= $data['usuario']['area_solicitud']; ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombres" class="form-label">Usuario Solicitante</label>
                            <input type="text" class="form-control" id="" name=""
                                value="<?= $data['usuario']['nombres'] . ' ' . $data['usuario']['apellidos']; ?>"
                                disabled>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="identificacion" class="form-label">Correo</label>
                            <input type="text" class="form-control" id="" name=""
                                value="<?= $data['usuario']['correo_empresarial']; ?>" disabled>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="formulario" class="form-label">Formulario</label>
                            <input type="text" class="form-control" id="" name=""
                                value="<?= $data['usuario']['formulario_vacaciones']; ?>" disabled>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="revision_aprobador_1" class="form-label">Jefe Inmediato</label>
                        <select class="form-control" id="revision_aprobador_1" name="revision_aprobador_1">
                            <option value="<?= $data['usuario']['jefe_inmediato']; ?>">
                                <?= $data['usuario']['nombre_jefe']; ?>
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_categoria" class="form-label">Categoria</label>
                        <select class="form-control selectpicker" data-live-search="true" id="id_categoria_operativa"
                            name="id_categoria" required title="Seleccione una razón">
                            <!-- Opciones aquí -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comentario_solicitud" class="form-label">Comentario Solicitud</label>
                        <textarea class="form-control" id="comentario_solicitud" name="comentario_solicitud" rows="3"
                            placeholder="Escribe aquí una descripción detallada..." required></textarea>
                    </div>
                    <!-- Tabla para fechas -->
                    <div class="mb-3">
                        <label for="fechas" class="form-label">Fechas Solicitadas</label>
                        <table class="table table-bordered" id="tablaFechasOperativa">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Valor (1 o 0.5)</th>
                                    <th>Turno</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Fechas se agregarán dinámicamente aquí -->
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success" id="agregarFechaOperativa">Agregar Fecha</button>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
                    <button type="submit" id="btnSubmitOperativa" class="btn btn-primary">Confirmar Solicitud
                        <span id="spinerSubmitOperativa" class="spinner-border spinner-border-sm d-none"
                            role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para actualizar solicitud -->
<div class="modal fade" id="updateSolicitudModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Encabezado -->
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Editar Solicitud de Vacaciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <!-- Formulario -->
            <form id="updateSolicitudForm">
                <div class="modal-body">
                    <!-- ID Solicitud oculto -->
                    <input type="hidden" name="id_solicitud" id="edit_id_solicitud">
                    <input type="hidden" name="id_empleado" id="edit_id_empleado">
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label for="nombres" class="form-label">Usuario Solicitante</label>
                            <input type="text" class="form-control" id="" name=""
                                value="<?= $data['usuario']['nombres'] . ' ' . $data['usuario']['apellidos']; ?>"
                                disabled>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="identificacion" class="form-label">Correo</label>
                            <input type="text" class="form-control" id="" name=""
                                value="<?= $data['usuario']['correo_empresarial']; ?>" disabled>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="formulario" class="form-label">Formulario</label>
                            <input type="text" class="form-control" id="" name=""
                                value="<?= $data['usuario']['formulario_vacaciones']; ?>" disabled>
                        </div>
                    </div>
                    <!-- Responsable de Aprobación -->
                    <?php if ($data['usuario']['formulario_vacaciones'] === "Operativa") { ?>
                        <div class="mb-3">
                            <label for="revision_aprobador_1" class="form-label">Jefe Inmediato</label>
                            <select class="form-control" id="revision_aprobador_1" name="revision_aprobador_1">
                                <option value="<?= $data['usuario']['jefe_inmediato']; ?>">
                                    <?= $data['usuario']['nombre_jefe']; ?>
                                </option>
                            </select>
                        </div>
                    <?php } ?>
                    <?php if ($data['usuario']['formulario_vacaciones'] === "Administrativo") { ?>

                        <div class="mb-3">
                            <label for="edit_responsable_aprobacion" class="form-label">Responsable de Aprobación</label>
                            <select class="form-control" name="responsable_aprobacion" id="edit_responsable_aprobacion"
                                required>
                                <!-- Opciones cargadas dinámicamente -->
                            </select>
                        </div>
                    <?php } ?>

                    <div class="mb-3">
                        <label for="edit_id_categoria" class="form-label">Categoria</label>
                        <select class="form-control" name="id_categoria" id="edit_id_categoria" required>
                            <!-- Opciones cargadas dinámicamente -->
                        </select>
                    </div>
                    <!-- Comentario -->
                    <div class="mb-3">
                        <label for="edit_comentario_solicitud" class="form-label">Comentario</label>
                        <textarea class="form-control" name="comentario_solicitud" id="edit_comentario_solicitud"
                            rows="3" placeholder="Escribe un comentario..."></textarea>
                    </div>
                    <!-- Tabla de Fechas -->
                    <h6 class="fw-bold mb-3">Fechas Solicitadas</h6>
                    <table id="tablaFechasEdit" class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Fecha</th>
                                <th>Valor</th>
                                <th>Día</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Filas de fechas dinámicamente añadidas aquí -->
                        </tbody>
                    </table>
                    <!-- Botón para agregar fecha -->
                    <div class="text-end mb-3">
                        <button type="button" class="btn btn-success" id="agregarFechaEdit">
                            <i class="fas fa-plus"></i> Agregar Fecha
                        </button>
                    </div>
                </div>
                <!-- Footer con botones de acción -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
                    <button type="submit" id="btnSubmitUpdate" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                        <span id="spinerUpdate" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Cancelar Solicitud -->
<div class="modal fade" id="canceledSolicitudModal" tabindex="-1" aria-labelledby="canceledSolicitudModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="canceledSolicitudModalLabel">Cancelar Solicitud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCancelSolicitud">
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas cancelar esta solicitud?</p>
                    <input type="hidden" id="id_solicitud" name="id_solicitud">
                    <div class="form-group">
                        <label for="cancelComentario">Razón de cancelación:</label>
                        <textarea class="form-control" id="cancelComentario" name="comentario" rows="3"
                            required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="btnSubmitCancel" type="submit" class="btn btn-danger">
                        Cancelar Solicitud
                        <span id="spinerCancel" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="consumidoSolicitudModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Encabezado -->
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Solicitud Aprobada</h5>
                <button type="button" class="btn btn-light btn-close" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- ID Solicitud oculto -->
                <input type="hidden" name="id_solicitud_consumido_operativo" id="id_solicitud_consumido_operativo">
                <!-- Responsable de Aprobación -->
                <div class="mb-3">
                    <label for="nombre_completo_consumido_operativa" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="nombre_completo_consumido_operativa" name="" readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_1_consumido" class="form-label">Aprobador 1</label>
                    <input type="text" class="form-control" id="revision_aprobador_1_consumido" name="" readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_2_consumido" class="form-label">Aprobador 2</label>
                    <input type="text" class="form-control" id="revision_aprobador_2_consumido" name="" readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_3_consumido" class="form-label">Aprobador 3</label>
                    <input type="text" class="form-control" id="revision_aprobador_3_consumido" name="" readonly>
                </div>
                <div class="mb-3">
                    <label for="asunto_consumido_operativa" class="form-label">Categoria</label>
                    <input type="text" class="form-control" id="asunto_consumido_operativa" name="" readonly>
                </div>
                <!-- Comentario -->
                <div class="mb-3">
                    <label for="comentario_solicitud_consumido_operativa" class="form-label">Comentario
                        Solicitud</label>
                    <textarea class="form-control" name="" id="comentario_solicitud_consumido_operativa" rows="3"
                        readonly></textarea>
                </div>
                <div class="mb-3">
                    <label for="comentario_respuesta_consumido" class="form-label">Comentario Respuesta</label>
                    <textarea class="form-control" name="" id="comentario_respuesta_consumido" rows="3"
                        readonly></textarea>
                </div>
                <!-- Tabla de Fechas -->
                <h6 class="fw-bold mb-3">Fechas Solicitadas</h6>
                <table id="tablaFechasConsumido" class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Valor</th>
                            <th>Día</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas de fechas dinámicamente añadidas aquí -->
                    </tbody>
                </table>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-warning" id="btnReversionOperativa" data-bs-toggle="modal"
                        data-bs-target="#reversionOperativa">
                        Solicitar Reversión
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reversionOperativa" tabindex="-1" aria-labelledby="reversionOperativaLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="ReversionForm">
                <div class="modal-header bg-black text-white">
                    <h5 class="modal-title" id="reversionOperativaLabel">
                        Solicitar Reversión de Días
                    </h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning text-center">
                        <strong>Atención:</strong> Se realizará una reversión de la siguiente solicitud.
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="id_numero_solicitud_reversion_operativa" class="form-label fw-bold">No. de
                                Solicitud</label>
                            <input type="text" class="form-control text-center fw-semibold"
                                name="id_solicitud_reversion" id="id_numero_solicitud_reversion_operativa" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="fecha_solicitud_reversion_operativa" class="form-label fw-bold">Fecha de
                                Solicitud</label>
                            <input type="text" class="form-control text-center fw-semibold"
                                id="fecha_solicitud_reversion_operativa" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="asunto_reversion_operativa" class="form-label fw-bold">Asunto de
                                Solicitud</label>
                            <input type="text" class="form-control text-center fw-semibold"
                                id="asunto_reversion_operativa" readonly>
                        </div>
                    </div>
                    <input type="hidden" name="comentario_solicitud" id="comentario_solicitud_reversion">
                    <table id="tablaFechasConsumido" class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Fecha</th>
                                <th>Valor</th>
                                <th>Día</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Filas de fechas dinámicamente añadidas aquí -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button id="btnSubmitReversion" type="submit" class="btn btn-success">
                        Confirmar Reversión
                        <span id="spinerReversion" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="revertidoSolicitudModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Encabezado -->
            <div class="modal-header bg-warning text-black">
                <h5 class="modal-title">Solicitud de Reversion </h5>
                <button type="button" class="btn btn-light btn-close" data-bs-dismiss="modal" aria-label="Cerrar">
                </button>
            </div>
            <div class="modal-body">
                <!-- ID Solicitud oculto -->
                <input type="hidden" name="id_solicitud_revertido" id="id_solicitud_revertido">
                <!-- Responsable de Aprobación -->
                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Colaborador</label>
                    <input type="text" class="form-control" id="nombre_completo_revertido"
                        name="nombre_completo_revertido" readonly>
                </div>
                <div class="mb-3">
                    <label for="jefe_aprobador_revertido" class="form-label">Responsable de Aprobación</label>
                    <input type="text" class="form-control" id="jefe_aprobador_revertido"
                        name="jefe_aprobador_revertido" readonly>
                </div>
                <div class="mb-3">
                    <label for="revertido_aprobador_1" class="form-label">Aprobador No.1</label>
                    <input type="text" class="form-control" id="revertido_aprobador_1" name="revertido_aprobador_1"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="revertido_aprobador_2" class="form-label">Aprobador No.2</label>
                    <input type="text" class="form-control" id="revertido_aprobador_2" name="revertido_aprobador_2"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="revertido_aprobador_3" class="form-label">Aprobador No.3</label>
                    <input type="text" class="form-control" id="revertido_aprobador_3" name="revertido_aprobador_3"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="asunto_revertido" class="form-label">Categoria</label>
                    <input type="text" class="form-control" id="asunto_revertido" name="" readonly>
                </div>
                <!-- Comentario -->
                <!-- Tabla de Fechas -->
                <h6 class="fw-bold mb-3">Fechas Solicitadas</h6>
                <table id="tablaFechasRevertido" class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Valor</th>
                            <th>Día</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas de fechas dinámicamente añadidas aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectSolicitud" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Encabezado -->
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Solicitud Rechazada</h5>
                <button type="button" class="btn btn-light btn-close" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- ID Solicitud oculto -->
                <input type="hidden" name="id_solicitud" id="id_solicitud">
                <!-- Responsable de Aprobación -->
                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="nombre_completo_reject_operativa" name="" readonly>
                </div>
                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Responsable</label>
                    <input type="text" class="form-control" id="jefe_aprobador_reject" name="" readonly>
                </div>
                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Aprobador 1</label>
                    <input type="text" class="form-control" id="revision_aprobador_1_reject" name="" readonly>
                </div>
                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Aprobador 2</label>
                    <input type="text" class="form-control" id="revision_aprobador_2_reject" name="" readonly>
                </div>
                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Aprobador 3</label>
                    <input type="text" class="form-control" id="revision_aprobador_3_reject" name="" readonly>
                </div>
                <div class="mb-3">
                    <label for="asunto" class="form-label">Categoria</label>
                    <input type="text" class="form-control" id="asunto_reject_operativa" name="" readonly>
                </div>
                <!-- Comentario -->
                <div class="mb-3">
                    <label for="comentario_solicitud_reject" class="form-label">Comentario Solicitud</label>
                    <textarea class="form-control" name="comentario_solicitud_reject" id="comentario_solicitud_reject"
                        rows="3" readonly></textarea>
                </div>
                <div class="mb-3">
                    <label for="comentario_respuesta_reject" class="form-label">Comentario Rechazo</label>
                    <textarea class="form-control" name="comentario_respuesta_reject" id="comentario_respuesta_reject"
                        rows="2" readonly></textarea>
                </div>
                <!-- Tabla de Fechas -->
                <h6 class="fw-bold mb-3">Fechas Solicitadas</h6>
                <table id="tablaFechasReject" class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Valor</th>
                            <th>Día</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas de fechas dinámicamente añadidas aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cancelSolicitudModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Encabezado -->
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Solicitud Cancelada </h5>
                <button type="button" class="btn btn-light btn-close" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- ID Solicitud oculto -->
                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="nombre_completo_cancel" name="nombre_completo" readonly>
                </div>
                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Responsable</label>
                    <input type="text" class="form-control" id="jefe_aprobador_cancel" name="" readonly>
                </div>
                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Aprobador 1</label>
                    <input type="text" class="form-control" id="revision_aprobador_1_cancel" name="" readonly>
                </div>
                <div class="mb-3">
                    <label for="asunto" class="form-label">Categoria</label>
                    <input type="text" class="form-control" id="asunto_cancel" name="asunto" readonly>
                </div>
                <!-- Comentario -->
                <div class="mb-3">
                    <label for="solicitud_cancel" class="form-label">Razon Cancelacion</label>
                    <textarea class="form-control" name="solicitud_cancel" id="solicitud_cancel" rows="3"
                        readonly></textarea>
                </div>
                <!-- Tabla de Fechas -->
                <h6 class="fw-bold mb-3">Fechas Solicitadas</h6>
                <table id="tablaFechasCancel" class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Valor</th>
                            <th>Día</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas de fechas dinámicamente añadidas aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>