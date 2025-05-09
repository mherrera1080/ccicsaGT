<?php headerAdmin($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/info.css" />
<div class="main p-3">
    <div style="height: 25px;" class="text-center"></div>

    <div class="container-fluid">
        <!-- Page Heading -->

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2>Solicitudes General</h2>
            </div>
            <div class="card-header d-flex justify-content-between align-items-center">
                <select id="mesSelector" class="form-select">
                    <option value="1">Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
            </div>
        </div>
        <!-- Content Row -->
        <div class="row">
    <div class="d-flex flex-wrap gap-3 justify-content-center">
        <!-- Revisadas (Mes) Card -->
        <div class="card border-left-primary shadow h-100 py-2 flex-fill" style="min-width: 200px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Revisadas (Mes)
                        </div>
                        <div class="info h5 mb-0 font-weight-bold text-gray-800">0</div>
                    </div>
                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
        <!-- Aprobadas (Mes) Card -->
        <div class="card border-left-success shadow h-100 py-2 flex-fill" style="min-width: 200px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Aprobadas (Mes)
                        </div>
                        <div class="approve h5 mb-0 font-weight-bold text-gray-800">0</div>
                    </div>
                    <i class="fa-solid fa-thumbs-up fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
        <!-- Rechazadas (Mes) Card -->
        <div class="card border-left-danger shadow h-100 py-2 flex-fill" style="min-width: 200px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Rechazadas (Mes)
                        </div>
                        <div class="reject h5 mb-0 font-weight-bold text-gray-800">0</div>
                    </div>
                    <i class="fa-solid fa-thumbs-down fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
        <!-- Pendientes Card -->
        <div class="card border-left-warning shadow h-100 py-2 flex-fill" style="min-width: 200px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pendientes
                        </div>
                        <div class="pendiente h5 mb-0 font-weight-bold text-gray-800">0</div>
                    </div>
                    <i class="fa-solid fa-hourglass-half fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
        <!-- Canceladas (Mes) Card -->
        <div class="card border-left-danger shadow h-100 py-2 flex-fill" style="min-width: 200px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Canceladas (Mes)
                        </div>
                        <div class="canceled h5 mb-0 font-weight-bold text-gray-800">0</div>
                    </div>
                    <i class="fa-solid fa-ban fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
        <!-- Revertidas (Mes) Card -->
        <div class="card border-left-info shadow h-100 py-2 flex-fill" style="min-width: 200px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Reversiones (Mes)
                        </div>
                        <div class="revertido h5 mb-0 font-weight-bold text-gray-800">0</div>
                    </div>
                    <i class="fa-solid fa-rotate-left fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Content Row -->

        <div class="row">

            <div class=" container-fluid mt-4">
                <div style="height: 15px;"></div>
                <!-- Contenido adicional de la vista -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fa-solid fa-people-arrows"></i> Solicitud de Vacaciones
                            </div>
                            <div class="card-body">
                                <input type="hidden" id="id_empleado">
                                <table id="tableVacaciones" class="table table-striped table-bordered nowrap"
                                    style="width:100%">
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

        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Content Column -->

            <div class="col-lg-6 mb-4">


            </div>
        </div>

    </div>

    <?php footerAdmin($data); ?>
    <script src="<?= base_url(); ?>/Assets/js/scripts.js"></script>




</div>


<div class="modal fade" id="infoSolicitudModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Encabezado -->
            <div class="modal-header bg-warning text-white">
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
                    <input type="text" class="form-control" id="nombre_completo_info" name="nombre_completo" readonly>
                </div>
                <div class="mb-3">
                    <label for="jefe_aprobador" class="form-label">Responsable de Aprobación</label>
                    <input type="text" class="form-control" id="jefe_aprobador_info" name="jefe_aprobador" readonly>
                </div>
                <div class="mb-3">
                    <label for="reversion_aprobador_1_cancel" class="form-label">Aprobador 1</label>
                    <input type="text" class="form-control" id="reversion_aprobador_1_info" name="revision_aprobador_1"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_2" class="form-label">Aprobador 2</label>
                    <input type="text" class="form-control" id="reversion_aprobador_2_info" name="revision_aprobador_2"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_3" class="form-label">Aprobador 3</label>
                    <input type="text" class="form-control" id="reversion_aprobador_3_info" name="revision_aprobador_3"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="asunto" class="form-label">Categoria</label>
                    <input type="text" class="form-control" id="asunto_info" name="asunto" readonly>
                </div>
                <!-- Comentario -->
                <div class="mb-3">
                    <label for="solicitud_info" class="form-label">Comentario</label>
                    <textarea class="form-control" name="solicitud_info" id="solicitud_info" rows="3"
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
                    <label for="reversion_aprobador_1" class="form-label">Aprobador 1</label>
                    <input type="text" class="form-control" id="reversion_aprobador_1_consumido"
                        name="revision_aprobador_1" readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_2" class="form-label">Aprobador 2</label>
                    <input type="text" class="form-control" id="reversion_aprobador_2_consumido"
                        name="revision_aprobador_2" readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_3" class="form-label">Aprobador 3</label>
                    <input type="text" class="form-control" id="reversion_aprobador_3_consumido"
                        name="revision_aprobador_3" readonly>
                </div>
                <div class="mb-3">
                    <label for="asunto" class="form-label">Categoria</label>
                    <input type="text" class="form-control" id="asunto_consumido" name="asunto" readonly>
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
                    <label for="jefe_aprobador_reject" class="form-label">Aprobador 1</label>
                    <input type="text" class="form-control" id="reversion_aprobador_1_reject"
                        name="revision_aprobador_1" readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_2" class="form-label">Aprobador 2</label>
                    <input type="text" class="form-control" id="reversion_aprobador_2_reject"
                        name="revision_aprobador_2" readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_3" class="form-label">Aprobador 3</label>
                    <input type="text" class="form-control" id="reversion_aprobador_3_reject"
                        name="revision_aprobador_3" readonly>
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
                <h5 class="modal-title">Solicitud Cancelada </h5>
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
                    <label for="reversion_aprobador_1_cancel" class="form-label">Aprobador 1</label>
                    <input type="text" class="form-control" id="reversion_aprobador_1_cancel"
                        name="revision_aprobador_1" readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_2" class="form-label">Aprobador 2</label>
                    <input type="text" class="form-control" id="revision_aprobador_2_cancel" name="revision_aprobador_2"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="revision_aprobador_3" class="form-label">Aprobador 3</label>
                    <input type="text" class="form-control" id="revision_aprobador_3_cancel" name="revision_aprobador_3"
                        readonly>
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

<div class="modal fade" id="reversionSolicitudModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Encabezado -->
            <div class="modal-header bg-warning text-black">
                <h5 class="modal-title">Solicitud de Reversion </h5>
                <button type="button" class="btn btn-light btn-close" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <form id="reversionForm">
                <div class="modal-body">

                    <!-- ID Solicitud oculto -->
                    <input type="hidden" name="id_solicitud_reversion" id="id_solicitud_reversion">
                    <input type="hidden" name="comentario_respuesta" id="respuesta_reversion">
                    <input type="hidden" name="categoria_solicitud" id="categoria_solicitud_reversion">
                    <!-- Responsable de Aprobación -->
                    <div class="mb-3">
                        <label for="nombre_completo" class="form-label">Colaborador</label>
                        <input type="text" class="form-control" id="nombre_completo_reversion"
                            name="nombre_completo_reversion" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="jefe_aprobador_reversion" class="form-label">Responsable de Aprobación</label>
                        <input type="text" class="form-control" id="jefe_aprobador_reversion"
                            name="jefe_aprobador_reversion" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="reversion_aprobador_1" class="form-label">Aprobador No.1</label>
                        <input type="text" class="form-control" id="reversion_aprobador_1" name="reversion_aprobador_1"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label for="reversion_aprobador_2" class="form-label">Aprobador No.2</label>
                        <input type="text" class="form-control" id="reversion_aprobador_2" name="reversion_aprobador_2"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label for="reversion_aprobador_3" class="form-label">Aprobador No.3</label>
                        <input type="text" class="form-control" id="reversion_aprobador_3" name="reversion_aprobador_3"
                            readonly>
                    </div>

                    <div class="mb-3">
                        <label for="asunto_reversion" class="form-label">Categoria</label>
                        <input type="text" class="form-control" id="asunto_reversion" name="" readonly>
                    </div>
                    <!-- Comentario -->
                    <!-- Tabla de Fechas -->
                    <h6 class="fw-bold mb-3">Fechas Solicitadas</h6>
                    <table id="tablaFechasReversion" class="table table-bordered table-striped">
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