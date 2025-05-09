<?php headerAdmin($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />

<div class="main p-3">
    <main>
        <div style="height: 25px;"></div>
        <div class="container-fluid px-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Lista de Empresas para Contratistas</h4>
                <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                    data-bs-target="#createModal">
                    Añadir Empresa
                </button>
            </div>

            <!-- Modal de Crear Empresa -->
            <div class="modal fade" id="createModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="createModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="createForm">
                            <input type="hidden" id="create_id_empresa" name="id_empresa">
                            <div class="modal-header">
                                <h5 class="modal-title" id="createModalLabel">Añadir Empresa</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12">
                                    <label for="create_nombre" class="form-label">Nombre Empresa</label>
                                    <input type="text" class="form-control" id="create_nombre" name="nombre" required>
                                </div>
                                <div class="col-12">
                                    <label for="create_descripcion" class="form-label">Descripción</label>
                                    <input type="text" class="form-control" id="create_descripcion" name="descripcion"
                                        required>
                                </div>
                                <div class="col-12">
                                    <label for="create_nit" class="form-label">NIT</label>
                                    <input type="text" class="form-control" id="create_nit" name="nit" required>
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
                            <input type="hidden" id="edit_id_empresa" name="id_empresa">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editEmpresaModalLabel">Editar Empresa</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12">
                                    <label for="edit_nombre" class="form-label">Nombre Empresa</label>
                                    <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                                </div>
                                <div class="col-12">
                                    <label for="edit_descripcion" class="form-label">Descripción</label>
                                    <input type="text" class="form-control" id="edit_descripcion" name="descripcion"
                                        required>
                                </div>
                                <div class="col-12">
                                    <label for="edit_nit" class="form-label">NIT</label>
                                    <input type="text" class="form-control" id="edit_nit" name="nit" required>
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
                            <h4>Lista de Empresas</h4>
                        </div>
                        <div class="card-body">
                            <table id="TableEmpresa" class="table table-striped table-bordered" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripcion</th>
                                        <th>NIT</th>
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