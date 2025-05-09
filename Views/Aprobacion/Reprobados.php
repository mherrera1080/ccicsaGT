<?php headerAdmin($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />

<div class="main p-3">
    <main>
        <div style="height: 25px;"></div>
        <div class="container-fluid px-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Lista de Empleados Rechazados</h4>
            </div>
            <!-- Modal de Editar Empresa -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editEmpresaModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editForm">
                            <input type="hidden" id="id_aprobaciones" name="id_aprobaciones">
                            <input type="hidden" id="id_empleado" name="id_empleado">

                            <div class="modal-header">
                                <h5 class="modal-title" id="editEmpresaModalLabel">Rechazo de Empleado</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="max-height: 550px; overflow-y: auto;">
                                <div class="col-12">
                                    <label for="fecha_ingreso" class="form-label">Fecha Solicitud</label>
                                    <input type="text" class="form-control" id="fecha_ingreso" name="fecha_ingreso"
                                        disabled>
                                </div>
                                <div class="col-12">
                                    <label for="primer_apellido" class="form-label">Primer Apellido</label>
                                    <input type="text" class="form-control" id="primer_apellido" name="primer_apellido">
                                </div>
                                <div class="col-12">
                                    <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                                    <input type="text" class="form-control" id="segundo_apellido"
                                        name="segundo_apellido">
                                </div>
                                <div class="col-12">
                                    <label for="nombres" class="form-label">Nombres</label>
                                    <input type="text" class="form-control" id="nombres" name="nombres">
                                </div>
                                <div class="col-12">
                                    <label for="identificacion" class="form-label">No. Identificacion</label>
                                    <input type="text" class="form-control" id="identificacion" name="identificacion">
                                </div>
                                <div class="mb-3">
                                    <label for="puesto_contrato">Puesto Contrato</label>
                                    <select class="form-control selectpicker" data-live-search="true"
                                        id="puesto_contrato" name="puesto_contrato" title="Seleccione">
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="puesto_operativo">Puesto Operativo</label>
                                    <select class="form-control selectpicker" data-live-search="true"
                                        id="puesto_operativo" name="puesto_operativo" title="Seleccione">
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="departamento" class="form-label">Departamento</label>
                                    <select class="form-control selectpicker" data-live-search="true" id="departamento"
                                        name="departamento" title="Seleccione">
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="area" class="form-label">Area | LN</label>
                                    <select class="form-control selectpicker" data-live-search="true" id="area"
                                        name="area" title="Seleccione"> </select>
                                </div>
                                <div class="mb-3">
                                    <label for="salario_base" class="form-label">Salario Base</label>
                                    <input type="number" class="form-control" id="salario_base" name="salario_base">
                                </div>
                                <div class="mb-3">
                                    <label for="bonificacion" class="form-label">Bonificacion</label>
                                    <input type="number" class="form-control" id="bonificacion" name="bonificacion">
                                </div>
                                <div class="mb-3">
                                    <label for="kpi1" class="form-label">KPI 1</label>
                                    <input type="number" class="form-control" id="kpi1" name="kpi1">
                                </div>
                                <div class="mb-3">
                                    <label for="kpi2" class="form-label">KPI 2</label>
                                    <input type="number" class="form-control" id="kpi2" name="kpi2">
                                </div>
                                <div class="mb-3">
                                    <label for="kpi_max" class="form-label">KPI Bono Max.</label>
                                    <input type="number" class="form-control" id="kpi_max" name="kpi_max">
                                </div>
                                <div class="col-12">
                                    <hr> <!-- Línea horizontal -->
                                </div>
                                <div class="col-12">
                                    <label for="descripcion" class="form-label">Asunto de Rechazo</label>
                                    <textarea class="form-control" id="descripcion" rows="3"
                                        placeholder="Escribe aquí una descripción detallada..." disabled></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <?php if (!empty($_SESSION['permisos'][12]['eliminar'])) { ?>
                                    <button type="submit" class="btn btn-danger" name="action" value="no_approve">
                                        No Aprobar Empleado
                                    </button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                        No Aprobar Empleado
                                    </button>
                                <?php } ?>
                                <?php if (!empty($_SESSION['permisos'][12]['editar'])) { ?>
                                    <button type="submit" class="btn btn-primary" name="action" value="approve">
                                        Solicitar Nuevamente</button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                        Solicitar Nuevamente
                                    </button>
                                <?php } ?>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div style="height: 25px;"></div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4></h4>
                        </div>
                        <div class="card-body">
                            <table id="tableRechazos" class="table table-striped table-bordered" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha Ingreso</th>
                                        <th>Apellidos</th>
                                        <th>Nombres</th>
                                        <th>Identificacion</th>
                                        <th>Puesto Contrato</th>
                                        <th>Puesto Operativo</th>
                                        <th>Departamento Laboral</th>
                                        <th>Area Laboral</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Datos de empresas se insertarán aquí -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <?php footerAdmin($data); ?>
</div>

<script src="<?= base_url(); ?>/Assets/js/scripts.js"></script>