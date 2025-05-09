<?php headerAdmin($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />

<div class="main p-3">
    <main>
        <div style="height: 25px;"></div>
        <div class="container-fluid px-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Lista de Lineas de Negocios</h4>
                <?php if (!empty($_SESSION['permisos'][13]['crear'])) { ?>
                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                        data-bs-target="#createModal">
                        Añadir Linea de Negocio
                    </button>
                <?php } else { ?>
                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        Añadir Linea de Negocio
                    </button>
                <?php } ?>
            </div>

            <!-- Modal de Crear Empresa -->
            <div class="modal fade" id="createModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="createModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="createForm">
                            <input type="hidden" id="create_id_ln" name="id_ln">
                            <div class="modal-header">
                                <h5 class="modal-title" id="createModalLabel">Añadir Linea de Negocio</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12">
                                    <label for="create_nombre" class="form-label">Codigo LN</label>
                                    <input type="number" class="form-control" id="create_nombre" name="codigo" required>
                                </div>
                                <div class="col-12">
                                    <label for="create_descripcion" class="form-label">Descripción</label>
                                    <input type="text" class="form-control" id="create_descripcion" name="descripcion"
                                        required>
                                </div>
                                <div class="col-12">
                                    <label for="dimension" class="form-label">Dimension</label>
                                    <select class="form-select" id="dimension" name="dimension" required>
                                        <option selected disabled value="">Seleccione una Dimension...</option>
                                        <!-- Las opciones se llenarán dinámicamente -->
                                    </select>
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
                            <input type="hidden" id="edit_id_ln" name="id_ln">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editEmpresaModalLabel">Editar Empresa</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12">
                                    <label for="edit_codigo" class="form-label">Nombre Empresa</label>
                                    <input type="text" class="form-control" id="edit_codigo" name="codigo" required>
                                </div>
                                <div class="col-12">
                                    <label for="edit_descripcion" class="form-label">Descripción</label>
                                    <input type="text" class="form-control" id="edit_descripcion" name="descripcion"
                                        required>
                                </div>
                                <div class="col-12">
                                    <label for="edit_dimension" class="form-label">Dimension</label>
                                    <select class="form-select" id="edit_dimension" name="dimension" required>
                                        <option selected disabled value="">Seleccione una Dimension...</option>
                                        <!-- Las opciones se llenarán dinámicamente -->
                                    </select>
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
                            <h4>Lista de Linea de Negocios</h4>
                        </div>
                        <div class="card-body">
                            <table id="tableLN" class="table table-striped table-bordered" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>CODIGO</th>
                                        <th>Descripcion</th>
                                        <th>DIMENSION</th>
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