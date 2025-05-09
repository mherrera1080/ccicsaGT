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
        <div style="height: 50px;"></div>

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

<!-- -- MODAL DE APROBACION -->
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
                    <input type="hidden" name="categoria_solicitud" id="categoria_solicitud">
                    <!-- Responsable de Aprobación -->
                    <div class="mb-3">
                        <label for="nombre_completo" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="jefe_aprobador" class="form-label">Responsable de Aprobación</label>
                        <input type="text" class="form-control" id="jefe_aprobador_actualizar" name="jefe_aprobador" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="asunto" class="form-label">Categoria</label>
                        <input type="text" class="form-control" id="asunto" name="asunto" readonly>
                    </div>
                    <!-- Comentario -->
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
                    <button type="submit" id="btnSubmitAprobar" class="btn btn-success" name="estado" value="approve">
                        <i class="fas fa-check"></i> Aprobar
                        <span id="spinnerAprobar" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                    <button type="submit" id="btnSubmitRechazar" class="btn btn-danger" name="estado"
                        value="no_approve">
                        <i class="fas fa-times"></i> Rechazar
                        <span id="spinnerRechazar" class="spinner-border spinner-border-sm d-none" role="status"></span>
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
                    <label for="jefe_aprobador" class="form-label">Responsable de Aprobación</label>
                    <input type="text" class="form-control" id="jefe_aprobador_consumido" name="jefe_aprobador"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="asunto" class="form-label">Categoria</label>
                    <input type="text" class="form-control" id="asunto_consumido" name="asunto" readonly>
                </div>
                <!-- Comentario -->
                <div class="mb-3">
                    <label for="comentario_solicitud" class="form-label">Comentario Solicitud</label>
                    <textarea class="form-control" name="comentario_solicitud" id="comentario_solicitud" rows="3"
                        readonly></textarea>
                </div>
                <div class="mb-3">
                    <label for="comentario_respuesta_up" class="form-label">Comentario Respuesta</label>
                    <textarea class="form-control" name="comentario_respuesta_up" id="comentario_respuesta_up" rows="3"
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
                    <label for="nombre_completo_reject" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="nombre_completo_reject" name="nombre_completo_reject"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="jefe_aprobador_reject" class="form-label">Responsable de Aprobación</label>
                    <input type="text" class="form-control" id="jefe_aprobador_reject" name="jefe_aprobador_reject"
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
                    <label for="jefe_aprobador" class="form-label">Responsable de Aprobación</label>
                    <input type="text" class="form-control" id="jefe_aprobador_cancel" name="jefe_aprobador" readonly>
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