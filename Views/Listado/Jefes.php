<?php headerAdmin($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />

<div class="main p-3">
    <main>
        <div style="height: 25px;"></div>
        <div class="container-fluid px-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Lista de Jefes</h4>
                <?php if (!empty($_SESSION['permisos'][13]['crear'])) { ?>
                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                    data-bs-target="#createModal">
                    Añadir Jefe
                </button>
                <?php } else { ?>
                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        Añadir Jefe
                    </button>
                <?php } ?>
            </div>

            <!-- Modal de Crear Empresa -->
            <div class="modal fade" id="createModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="createModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="createForm">
                            <input type="hidden" id="create_id" name="id">
                            <div class="modal-header">
                                <h5 class="modal-title" id="createModalLabel">Añadir Jefe</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12">
                                    <label for="create_usuario" class="form-label">Empleado</label>
                                    <select class="form-select" id="create_usuario" name="usuario" required>
                                        <option selected disabled value="">Seleccione un Empleado...</option>
                                        <!-- Las opciones se llenarán dinámicamente -->
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="create_area" class="form-label">Puesto</label>
                                    <select class="form-select" id="create_area" name="area" required>
                                        <option selected disabled value="">Seleccione un Puesto...</option>
                                        <!-- Las opciones se llenarán dinámicamente -->
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="create_estado" class="form-label">Estado</label>
                                    <select class="form-select" id="create_estado" name="estado" required>
                                        <option selected disabled value="">Elige un Estado...</option>
                                        <option value="Activo">Activo</option>
                                        <option value="Inactivo">Inactivo</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor selecciona un estado válido.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal de Editar Empresa -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editEmpresaModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editForm">
                            <input type="hidden" id="edit_id" name="id">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editEmpresaModalLabel">Editar Jefe/Lider</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12">
                                    <label for="edit_usuario" class="form-label">Empleado</label>
                                    <select class="form-select" id="edit_usuario" name="usuario" required>
                                        <option selected disabled value="">Seleccione un Empleado...</option>
                                        <!-- Las opciones se llenarán dinámicamente -->
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="edit_puesto" class="form-label">Puesto</label>
                                    <select class="form-select" id="edit_area" name="area" required>
                                        <option selected disabled value="">Seleccione un Puesto...</option>
                                        <!-- Las opciones se llenarán dinámicamente -->
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="edit_estado" class="form-label">Estado</label>
                                    <select class="form-select" id="edit_estado" name="estado" required>
                                        <option selected disabled value="">Elige un Estado...</option>
                                        <option value="Activo">Activo</option>
                                        <option value="Inactivo">Inactivo</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor selecciona un estado válido.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
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
                            <h4>Lista de Jefes/Lideres</h4>
                        </div>
                        <div class="card-body">
                            <table id="tableJefes" class="table table-striped table-bordered" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripcion</th>
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