<?php headerAdmin($data); ?>

<div class="main p-3">
    <main>
        <div style="height: 25px;"></div>
        <div class="container-fluid px-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Lista de Roles para Usuarios</h4>

                <?php if (!empty($_SESSION['permisos'][8]['crear'])) { ?>
                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                    data-bs-target="#createModal">
                    Añadir Rol
                </button>
                <?php } else { ?>
                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        Añadir Rol
                    </button>
                <?php } ?>
            </div>

            <!-- Modal de Crear Rol -->
            <div class="modal fade" id="createModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="createModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="createForm">
                            <input type="hidden" id="create_id_empresa" name="id">
                            <div class="modal-header">
                                <h5 class="modal-title" id="createModalLabel">Añadir Rol</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="role_name" class="form-label">Nombre Rol</label>
                                    <input type="text" class="form-control" id="role_name" name="role_name" required>
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

            <!-- Modal de Editar Permisos -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editPermissionsModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPermissionsModalLabel">Editar Permisos</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- ID y Nombre del Rol -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="edit_role_name" class="form-label">Nombre del Rol</label>
                                    <input type="text" class="form-control" id="edit_role_name" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_id" class="form-label">ID del Rol</label>
                                    <input type="text" class="form-control" id="edit_id" readonly>
                                </div>
                            </div>

                            <!-- Permisos por Modulo en una Tabla Horizontal -->
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Módulo</th>
                                            <th>Crear</th>
                                            <th>Leer</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="editForm">
                                        <!-- Las filas se llenarán dinámicamente con JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" id="submit">Guardar Cambios</button>
                        </div>

                    </div>
                </div>
            </div>

            <div style="height: 25px;"></div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Lista de Roles</h4>
                        </div>
                        <div class="card-body">
                            <table id="tableRoles" class="table table-striped table-bordered" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Roles</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Datos de roles se insertarán aquí -->
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