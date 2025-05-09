<?php headerAdmin($data); ?>

<div class="main p-3">
    <main>
        <div style="height: 25px;"></div>
        <div class="container-fluid px-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Lista de Empleados por Aprobar</h4>
            </div>
            <!-- Modal de Editar Empresa -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editEmpresaModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editForm">
                            <input type="hidden" id="id_aprobaciones" name="id_aprobaciones">
                            <input type="hidden" id="estado" name="estado">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editEmpresaModalLabel">Solicitud de Empleado</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="max-height: 550px; overflow-y: auto;">
                                <div class="col-12">
                                    <label for="codigo_empleado" class="form-label">Código de Empleado</label>
                                    <input type="text" class="form-control" id="codigo_empleado" name="codigo_empleado"
                                        <?php echo !empty($usuario['codigo_empleado']) ? 'readonly' : ''; ?>
                                        value="<?= $usuario['codigo_empleado'] ?? ''; ?>">
                                </div>

                                <div class="col-12">
                                    <label for="primer_apellido" class="form-label">Primer Apellido </label>
                                    <input type="text" class="form-control" id="primer_apellido" name="primer_apellido"
                                        disabled>
                                </div>
                                <div class="col-12">
                                    <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                                    <input type="text" class="form-control" id="segundo_apellido"
                                        name="segundo_apellido" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="nombres" class="form-label">Nombres </label>
                                    <input type="text" class="form-control" id="nombres" name="nombres" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="identificacion" class="form-label">No. Identificacion</label>
                                    <input type="text" class="form-control" id="identificacion" name="identificacion"
                                        disabled>
                                </div>
                                <div class="col-12">
                                    <label for="puesto_contrato" class="form-label">Puesto Contrato</label>
                                    <input type="text" class="form-control" id="puesto_contrato" name="puesto_contrato"
                                        disabled>
                                </div>
                                <div class="col-12">
                                    <label for="puesto_operativo" class="form-label">Puesto Operativo </label>
                                    <input type="text" class="form-control" id="puesto_operativo"
                                        name="puesto_operativo" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="departamento" class="form-label">Departamento</label>
                                    <input type="text" class="form-control" id="departamento" name="departamento"
                                        disabled>
                                </div>
                                <div class="col-12">
                                    <label for="nombre_area" class="form-label">Area Laboral</label>
                                    <input type="text" class="form-control" id="nombre_area" name="nombre_area"
                                        disabled>
                                </div>
                                <div class="col-12">
                                    <label for="salario_base" class="form-label">Salario Base</label>
                                    <input type="text" class="form-control" id="salario_base" name="salario_base"
                                        disabled>
                                </div>
                                <div class="col-12">
                                    <label for="bonificacion" class="form-label">Bonificacion</label>
                                    <input type="text" class="form-control" id="bonificacion" name="bonificacion"
                                        disabled>
                                </div>
                                <div class="col-12">
                                    <label for="kpi1" class="form-label">KPI 1</label>
                                    <input type="text" class="form-control" id="kpi1" name="kpi1" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="kpi2" class="form-label">KPI 2</label>
                                    <input type="text" class="form-control" id="kpi2" name="kpi2" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="kpi_max" class="form-label">KPI Bono Max.</label>
                                    <input type="text" class="form-control" id="kpi_max" name="kpi_max" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="descripcion" class="form-label">Describir Asunto</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                        placeholder="Escribe aquí una descripción detallada..." required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <?php if (!empty($_SESSION['permisos'][11]['eliminar'])) { ?>
                                    <button type="submit" class="btn btn-danger" name="action" value="no_approve">
                                        No Aprobar Empleado
                                    </button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                        No Aprobar Empleado
                                    </button>
                                <?php } ?>
                                <?php if (!empty($_SESSION['permisos'][11]['crear'])) { ?>
                                    <button type="submit" class="btn btn-primary" name="action" value="approve">
                                        Aprobar Empleado
                                    </button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                        Aprobar Empleado
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
                        </div>
                        <div class="card-body">
                            <table id="tableAprobaciones" class="table table-striped table-bordered" style="width:100%">
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
                                        <th>Linea Negocio</th>
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
                <p class="text-muted">No cuentas con los permisos necesarios para acceder a esta funcion.</p>
            </div>
            <div class="modal-footer justify-content-center border-0"> <!-- Centrado y sin bordes -->
                <button type="button" class="btn btn-dark px-4 rounded-pill" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let role_id = <?= json_encode($_SESSION['role_id'] ?? 0); ?>;
    let permisos = <?= json_encode($_SESSION['permisos'] ?? []); ?>;
</script>