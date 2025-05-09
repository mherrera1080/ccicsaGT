<?php headerAdmin($data); ?>

<div class="main p-3">
    <div style="height: 25px;" class="text-center"></div>

    <div class=" container-fluid mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Personal en Nomina</h2>
        </div>
        <div class="row">
            <!-- Earnings (Monthly) Card Example -->
            <div class="d-flex flex-wrap justify-content-between">
                <!-- Revisadas (Mes) Card -->
                <div class="card border-left-primary shadow h-100 py-2 me-3 mb-3" style="flex: 1; max-width: 18%;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-auto">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Activos
                                </div>
                                <div class="info h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $data['nomina']['total_nomina'] ?>
                                </div>
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
                                    Hombres
                                </div>
                                <div class="info h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $data['nomina']['total_hombres'] ?>
                                </div>
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
                                    Mujeres
                                </div>
                                <div class="info h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $data['nomina']['total_mujeres'] ?>
                                </div>
                            </div>
                            <div>
                                <i class="fa-solid fa-person-dress fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="" style="flex: 1; max-width: 50%;">
                </div>
                
            </div>
        </div>

        <div style="height: 50px;"></div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa-solid fa-people-arrows"></i> Tabla del Personal
                    </div>
                    <div class="card-body">
                    <input type="hidden" id="role_id" value="<?= $_SESSION['PersonalData']['role_id']; ?>">
                        <table id="tablePersonal" class="table table-striped table-bordered nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Código Empleado</th>
                                    <th>Fecha Ingreso</th>
                                    <th>Apellidos</th>
                                    <th>Nombres</th>
                                    <th>Identificación</th>
                                    <th>Correo Empresarial</th>
                                    <th>Puesto Contrato</th>
                                    <th>Puesto Operativo</th>
                                    <th>Departamento</th>
                                    <th>Area Laboral</th>
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
</div>

<script>
    let role_id = <?= json_encode($_SESSION['role_id'] ?? 0); ?>;
    let permisos = <?= json_encode($_SESSION['permisos'] ?? []); ?>;
</script>


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmación de Baja</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="AddBajaForm">
                <input type="hidden" id="id_empleado" name="id_empleado">
                <div class="modal-body">
                    <p>¿Seguro que quieres dar de baja al siguiente usuario?</p>

                    <div class="mb-3">
                        <label for="fecha_salida" class="form-label">Fecha de Salida (Ultimo dia de trabajo)</label>
                        <input type="date" class="form-control" id="fecha_salida" name="fecha_salida" required>
                    </div>
                    <div class="mb-3">
                        <label for="razon_baja" class="form-label">Razón de baja</label>
                        <select class="form-control selectpicker" data-live-search="true" id="razon_baja"
                            name="razon_baja" required title="Seleccione una razón">
                            <!-- Opciones aquí -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comentario" class="form-label">Motivo de Baja</label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="3"
                            placeholder="Escribe aquí una descripción detallada..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <select class="form-control selectpicker" data-live-search="true" id="observaciones"
                            name="observaciones" required title="Seleccione una razón">
                            <option value="D">Directo</option>
                            <option value="I">Re-Ingreso</option>
                        </select required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Confirmar Baja</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var permisosUsuario = <?php echo json_encode($_SESSION['permisos']); ?>;
</script>
