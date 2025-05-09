<?php headerAdmin($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/info.css" />
<!-- LADO DEL JEFE -->
<div class="main p-3">
    <div class=" container-fluid mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Solicitudes Pendientes</h2>
            <h2><?= $data['jefe']['nombres']; ?> <?= $data['jefe']['apellidos']; ?></h2>

        </div>
        <div style="height: 20px;"></div>

        <div class="row">
            <!-- Earnings (Monthly) Card Example -->
            <div class="d-flex flex-wrap justify-content-between">
                <!-- Revisadas (Mes) Card -->
                <div class="card border-left-primary shadow h-100 py-2 me-3 mb-3" style="flex: 1; max-width: 18%;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-auto">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Revisadas
                                </div>
                                <div class="info h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $data['registro']['solicitudes_revisadas'] ?></div>
                            </div>
                            <div>
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Aprobadas (Mes) Card -->
                <div class="card border-left-success shadow h-100 py-2 me-3 mb-3" style="flex: 1; max-width: 18%;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-auto">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Aprobadas
                                </div>
                                <div class="info h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $data['registro']['solicitudes_aprobadas'] ?></div>
                            </div>
                            <div>
                                <i class="fa-solid fa-person-circle-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Rechazadas (Mes) Card -->
                <div class="card border-left-danger shadow h-100 py-2 me-3 mb-3" style="flex: 1; max-width: 18%;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-auto">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Rechazadas
                                </div>
                                <div class="info h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $data['registro']['solicitudes_rechazadas'] ?></div>
                            </div>
                            <div>
                                <i class="fa-solid fa-person-circle-minus fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pendientes Card -->
                <div class="card border-left-warning shadow h-100 py-2 me-3 mb-3" style="flex: 1; max-width: 18%;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-auto">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pendientes
                                </div>
                                <div class="info h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $data['registro']['solicitudes_pendientes'] ?></div>
                            </div>
                            <div>
                                <i class="fa-solid fa-hourglass-half fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Canceladas (Mes) Card -->
                <div class="card border-left-primary shadow h-100 py-2 mb-3" style="flex: 1; max-width: 18%;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-auto">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Canceladas
                                </div>
                                <div class="info h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $data['registro']['solicitudes_canceladas'] ?></div>
                            </div>
                            <div>
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                        <input type="hidden" id="id_empleado" value="<?= $data['jefe']['responsable']; ?>">
                        <table id="tableSolicitud" class="table table-striped table-bordered nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Empleado</th>
                                    <th>Codigo</th>
                                    <th>Razon Solicitud</th>
                                    <th>Dias</th>
                                    <th>Fecha Solicitud</th>
                                    <th>Fecha Revision</th>
                                    <th>Dias Retraso</th>
                                    <th>Estado</th>
                                    <th>Ver</th>
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


<div class="modal fade" id="updateSolicitudModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Encabezado -->
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Solicitud de Vacaciones</h5>
                <button type="button" class="btn btn-light btn-close" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <!-- Formulario -->
            <form id="updateSolicitudForm">
                <div class="modal-body">
                    <!-- ID Solicitud oculto -->
                    <input type="hidden" name="id_solicitud" id="id_solicitud">
                    <!-- Responsable de Aprobación -->
                    <div class="mb-3">
                        <label for="nombre_completo_update" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="nombre_completo_update" name="nombre_completo"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label for="jefe_aprobador_update" class="form-label">Responsable de Aprobación</label>
                        <input type="text" class="form-control" id="jefe_aprobador_update" name="jefe_aprobador"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label for="asunto" class="form-label">Categoria</label>
                        <input type="text" class="form-control" id="asunto" name="asunto" readonly>
                    </div>
                    <!-- Comentario -->
                    <div class="mb-3">
                        <label for="comentario_solicitud_update" class="form-label">Comentario Solicitud</label>
                        <textarea class="form-control" name="comentario_solicitud_update"
                            id="comentario_solicitud_update" rows="3" readonly></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="comentario_respuesta" class="form-label">Comentario</label>
                        <textarea class="form-control" name="comentario_respuesta" id="comentario_respuesta" rows="3"
                            placeholder="Escribe un comentario..."></textarea>
                    </div>
                    <!-- Tabla de Fechas -->
                    <h6 class="fw-bold mb-3">Fechas Solicitadas</h6>
                    <table id="tablaFechasEdit" class="table table-bordered table-striped">
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
                <!-- Footer con botones de acción -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="estado" value="approve">
                        <i class="fas fa-check"></i> Aprobar
                    </button>
                    <button type="submit" class="btn btn-danger" name="estado" value="no_approve">
                        <i class="fas fa-times"></i> Rechazar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="updateSolicitudOperativaModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Encabezado -->
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Solicitud de Vacaciones Fase 1</h5>
                <button type="button" class="btn btn-light btn-close" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <!-- Formulario -->
            <form id="updateSolicitudOperativaForm">
                <div class="modal-body">
                    <!-- ID Solicitud oculto -->
                    <input type="hidden" name="id_solicitud_operativa" id="id_solicitud_operativa">
                    <!-- Responsable de Aprobación -->
                    <div class="mb-3">
                        <label for="nombre_completo" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="nombre_completo_operativa" name="nombre_completo"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label for="revision_aprobador_2" class="form-label">Jefe Inmediato</label>
                        <select class="form-control" id="revision_aprobador_2" name="revision_aprobador_2">
                            <option value="<?= $data['jefe']['jefe_inmediato']; ?>">
                                <?= $data['jefe']['nombre_jefe']; ?>
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="asunto" class="form-label">Categoria</label>
                        <input type="text" class="form-control" id="asunto_operativa" name="asunto" readonly>
                    </div>
                    <!-- Comentario -->
                    <div class="mb-3">
                        <label for="comentario_solicitud_update_operativa" class="form-label">Comentario Solicitud</label>
                        <textarea class="form-control" name="comentario_solicitud_update_operativa"
                            id="comentario_solicitud_update_operativa" rows="3" readonly></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="comentario_respuesta" class="form-label">Comentario Respuesta</label>
                        <textarea class="form-control" name="comentario_respuesta" id="comentario_respuesta" rows="3"
                            placeholder="Escribe un comentario..."></textarea>
                    </div>
                    <!-- Tabla de Fechas -->
                    <h6 class="fw-bold mb-3">Fechas Solicitadas</h6>
                    <table id="tablaFechasOperativoEdit" class="table table-bordered table-striped">
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
                <!-- Footer con botones de acción -->
                <div class="modal-footer">
                    <button type="submit" id="btnSubmitAprobarOperativa" class="btn btn-success" name="estado"
                        value="approve">
                        <i class="fas fa-check"></i> Aprobar
                        <span id="spinnerAprobarOperativa" class="spinner-border spinner-border-sm d-none"
                            role="status"></span>
                    </button>
                    <button type="submit" id="btnSubmitRechazarOperativa" class="btn btn-danger" name="estado"
                        value="no_approve">
                        <i class="fas fa-times"></i> Rechazar
                        <span id="spinnerRechazarOperativa" class="spinner-border spinner-border-sm d-none"
                            role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="updateSolicitudOperativaJefeModal" tabindex="-1" data-bs-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Encabezado -->
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Solicitud de Vacaciones Fase 2</h5>
                <button type="button" class="btn btn-light btn-close" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <!-- Formulario -->
            <form id="updateSolicitudOperativaJefeForm">
                <div class="modal-body">
                    <!-- ID Solicitud oculto -->
                    <input type="hidden" name="id_revision_aprobador_2" id="id_revision_aprobador_2">
                    <!-- Responsable de Aprobación -->
                    <div class="mb-3">
                        <label for="nombre_completo" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="nombre_completo_jefe" name="nombre_completo"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label for="revision_aprobador_3" class="form-label">Jefe Inmediato</label>
                        <select class="form-control" id="revision_aprobador_3" name="revision_aprobador_3">
                            <option value="<?= $data['jefe']['jefe_inmediato']; ?>">
                                <?= $data['jefe']['nombre_jefe']; ?>
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="asunto_jefe" class="form-label">Categoria</label>
                        <input type="text" class="form-control" id="asunto_jefe" name="asunto_jefe" readonly>
                    </div>
                    <!-- Comentario -->
                    <div class="mb-3">
                        <label for="comentario_respuesta" class="form-label">Comentario</label>
                        <textarea class="form-control" name="comentario_respuesta" id="comentario_respuesta" rows="3"
                            placeholder="Escribe un comentario..."></textarea>
                    </div>
                    <!-- Tabla de Fechas -->
                    <h6 class="fw-bold mb-3">Fechas Solicitadas</h6>
                    <table id="tablaFechasOperativoEdit" class="table table-bordered table-striped">
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
                <!-- Footer con botones de acción -->
                <div class="modal-footer">
                    <button type="submit" id="btnSubmitAprobarJefe" class="btn btn-success" name="estado"
                        value="approve">
                        <i class="fas fa-check"></i> Aprobar
                        <span id="spinnerAprobarJefe" class="spinner-border spinner-border-sm d-none"
                            role="status"></span>
                    </button>
                    <button type="submit" id="btnSubmitRechazarJefe" class="btn btn-danger" name="estado"
                        value="no_approve">
                        <i class="fas fa-times"></i> Rechazar
                        <span id="spinnerRechazarJefe" class="spinner-border spinner-border-sm d-none"
                            role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="updateSolicitudOperativaGerenteModal" tabindex="-1" data-bs-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Encabezado -->
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Solicitud de Vacaciones Fase 3</h5>
                <button type="button" class="btn btn-light btn-close" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <!-- Formulario -->
            <form id="updateSolicitudOperativaGerenteForm">
                <div class="modal-body">
                    <!-- ID Solicitud oculto -->
                    <input type="hidden" name="id_revision_aprobador_3" id="id_revision_aprobador_3">
                    <input type="hidden" name="categoria_solicitud" id="categoria_solicitud">
                    <!-- Responsable de Aprobación -->
                    <div class="mb-3">
                        <label for="nombre_completo" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="nombre_completo_gerente" name="nombre_completo"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label for="revision_aprobador_1_gerente" class="form-label">Aprobador 1</label>
                        <input type="text" class="form-control" id="revision_aprobador_1_gerente"
                            name="revision_aprobador_1_gerente" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="revision_aprobador_2_gerente" class="form-label">Aprobador 2</label>
                        <input type="text" class="form-control" id="revision_aprobador_2_gerente"
                            name="revision_aprobador_2_gerente" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="revision_aprobador_3_gerente" class="form-label">Aprobador 3</label>
                        <input type="text" class="form-control" id="revision_aprobador_3_gerente"
                            name="revision_aprobador_3" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="asunto_jefe" class="form-label">Categoria</label>
                        <input type="text" class="form-control" id="asunto_gerente" name="asunto_jefe" readonly>
                    </div>
                    <!-- Comentario -->
                    <div class="mb-3">
                        <label for="comentario_respuesta" class="form-label">Comentario</label>
                        <textarea class="form-control" name="comentario_respuesta" id="comentario_respuesta" rows="3"
                            placeholder="Escribe un comentario..."></textarea>
                    </div>
                    <!-- Tabla de Fechas -->
                    <h6 class="fw-bold mb-3">Fechas Solicitadas</h6>
                    <table id="tablaFechasOperativoEdit" class="table table-bordered table-striped">
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
                <!-- Footer con botones de acción -->
                <div class="modal-footer">
                    <button type="submit" id="btnSubmitAprobarGerente" class="btn btn-success" name="estado"
                        value="approve">
                        <i class="fas fa-check"></i> Aprobar
                        <span id="spinnerAprobarGerente" class="spinner-border spinner-border-sm d-none"
                            role="status"></span>
                    </button>
                    <button type="submit" id="btnSubmitAprobarGerente" class="btn btn-danger" name="estado"
                        value="no_approve">
                        <i class="fas fa-times"></i> Rechazar
                        <span id="spinnerAprobarGerente" class="spinner-border spinner-border-sm d-none"
                            role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="infoOperativaModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Encabezado -->
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Solicitud de Vacaciones </h5>
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
                    <label for="revision_aprobador_1" class="form-label">Aprobacion No. 1</label>
                    <input type="text" class="form-control" id="revision_aprobador_1_info" name="revision_aprobador_1"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_2" class="form-label">Aprobacion No. 2</label>
                    <input type="text" class="form-control" id="revision_aprobador_2_info" name="revision_aprobador_2"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_3" class="form-label">Aprobacion No. 3</label>
                    <input type="text" class="form-control" id="revision_aprobador_3_info" name="revision_aprobador_3"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="asunto" class="form-label">Categoria</label>
                    <input type="text" class="form-control" id="asunto_info" name="asunto" readonly>
                </div>
                <!-- Comentario -->
                <div class="mb-3">
                    <label for="comentario_solicitud_info" class="form-label">Comentario Solicitud </label>
                    <textarea class="form-control" name="comentario_solicitud_info" id="comentario_solicitud_info"
                        rows="3" readonly></textarea>
                </div>
                <!-- Tabla de Fechas -->
                <h6 class="fw-bold mb-3">Fechas Solicitadas</h6>
                <table id="tablaFechasEdit" class="table table-bordered table-striped">
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

<div class="modal fade" id="consumidoSolicitudModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Encabezado -->
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Solicitud de Vacaciones</h5>
                <button type="button" class="btn btn-light btn-close" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- ID Solicitud oculto -->
                <input type="hidden" name="id_solicitud" id="id_solicitud">
                <!-- Responsable de Aprobación -->
                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="nombre_completo_consumido" name="nombre_completo"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_1" class="form-label">Aprobador 1</label>
                    <input type="text" class="form-control" id="revision_aprobador_1_consumido"
                        name="revision_aprobador_1" readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_2" class="form-label">Aprobador 2</label>
                    <input type="text" class="form-control" id="revision_aprobador_2_consumido"
                        name="revision_aprobador_2" readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_3" class="form-label">Aprobador 3</label>
                    <input type="text" class="form-control" id="revision_aprobador_3_consumido"
                        name="revision_aprobador_3" readonly>
                </div>
                <div class="mb-3">
                    <label for="asunto" class="form-label">Categoria</label>
                    <input type="text" class="form-control" id="asunto_consumido" name="asunto_consumido" readonly>
                </div>
                <!-- Comentario -->
                <div class="mb-3">
                    <label for="solicitud_consumido" class="form-label">Comentario</label>
                    <textarea class="form-control" name="solicitud_consumido" id="solicitud_consumido" rows="3"
                        readonly></textarea>
                </div>
                <!-- Tabla de Fechas -->
                <h6 class="fw-bold mb-3">Fechas Solicitadas</h6>
                <table id="tablaFechasEdit" class="table table-bordered table-striped">
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
                <h5 class="modal-title">Solicitud de Vacaciones</h5>
                <button type="button" class="btn btn-light btn-close" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- ID Solicitud oculto -->
                <input type="hidden" name="id_solicitud" id="id_solicitud">
                <!-- Responsable de Aprobación -->
                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="nombre_completo_reject" name="nombre_completo" readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_1" class="form-label">Aprobador 1</label>
                    <input type="text" class="form-control" id="revision_aprobador_1_reject" name="revision_aprobador_1"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_2" class="form-label">Aprobador 2</label>
                    <input type="text" class="form-control" id="revision_aprobador_2_reject" name="revision_aprobador_2"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_3" class="form-label">Aprobador 3</label>
                    <input type="text" class="form-control" id="revision_aprobador_3_reject" name="revision_aprobador_3"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="asunto_reject" class="form-label">Categoria</label>
                    <input type="text" class="form-control" id="asunto_reject" name="asunto_reject" readonly>
                </div>
                <!-- Comentario -->
                <div class="mb-3">
                    <label for="solicitud_reject" class="form-label">Comentario Solicitud</label>
                    <textarea class="form-control" name="solicitud_reject" id="solicitud_reject" rows="2"
                        readonly></textarea>
                </div>
                <div class="mb-3">
                    <label for="respuesta_reject" class="form-label">Comentario Rechazo</label>
                    <textarea class="form-control" name="respuesta_reject" id="respuesta_reject" rows="2"
                        readonly></textarea>
                </div>
                <!-- Tabla de Fechas -->
                <h6 class="fw-bold mb-3">Fechas Solicitadas</h6>
                <table id="tablaFechasEdit" class="table table-bordered table-striped">
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
                <h5 class="modal-title">Solicitud de Vacaciones </h5>
                <button type="button" class="btn btn-light btn-close" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- ID Solicitud oculto -->
                <input type="hidden" name="id_solicitud" id="id_solicitud">
                <!-- Responsable de Aprobación -->
                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="nombre_completo_cancel" name="nombre_completo" readonly>
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
                <table id="tablaFechasEdit" class="table table-bordered table-striped">
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