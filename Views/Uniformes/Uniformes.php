<?php headerAdmin($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />

<div class="main p-3">
    <main>

        <div style="height: 25px;"></div>

        <div class="container-fluid px-4">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Lista de Uniformes del Usuario <strong><?= $data['uniformes']['nombre_completo']; ?></strong></h4>

                <?php if (!empty($_SESSION['permisos'][4]['crear'])) { ?>
                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                        data-bs-target="#addUniformeModal">
                        Añadir Uniformes
                    </button>
                <?php } ?>

            </div>
            <button class="btn btn-warning text-black"
                onclick="window.location.href='<?= base_url(); ?>/Personal/Avance/<?= $data['uniformes']['id_empleado']; ?>'">
                <i class="fas fa-arrow-left me-1"></i> Regresar
            </button>

            <div style="height: 25px;"></div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Lista de Uniformes</h4>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="id_empleado" value="<?= $data['uniformes']['id_empleado']; ?>">
                            <table id="TableUniformes" class="table table-striped table-bordered" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Camisas</th>
                                        <th>Pantalones</th>
                                        <th>Botas</th>
                                        <th>Fecha Asignacion</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="TableUniformes">
                                    <!-- Datos del personal se insertarán aquí -->
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

<script>
    let role_id = <?= json_encode($_SESSION['role_id'] ?? 0); ?>;
    let permisos = <?= json_encode($_SESSION['permisos'] ?? []); ?>;
</script>

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
                <p class="text-muted">No cuentas con los permisos necesarios para esta funcion.</p>
            </div>
            <div class="modal-footer justify-content-center border-0"> <!-- Centrado y sin bordes -->
                <button type="button" class="btn btn-dark px-4 rounded-pill" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Modal para añadir uniformes -->
<div class="modal fade" id="addUniformeModal" tabindex="-1" aria-labelledby="addUniformeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUniformeModalLabel">Añadir Uniforme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addUserForm">
                <div class="modal-body">
                    <input type="hidden" id="id_empleado" name="id_empleado"
                        value="<?= $data['uniformes']['id_empleado']; ?>">
                    <table class="table" id="modalTableUniformes">
                        <tbody>
                            <tr>
                                <td>
                                    <select class="form-select" id="modal_uniforme" name="uniforme[]" required>
                                        <option selected disabled value="">Seleccione una Prenda...</option>
                                    </select>
                                </td>
                                <td><input type="number" class="form-control" id="modal_cantidad" name="cantidad[]"
                                        placeholder="cantidad" required></td>
                                <td class="eliminar"> <input type="button" value="Menos -" class="btn btn-danger"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="insertar" value="Insertar Fila" class="btn btn-info">
                    <button type="submit" id="upUniformes" class="btn btn-primary">
                        Guardar
                        <span id="spinerUniformes" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal para generar/subir PDF -->
<div class="modal fade" id="generarPDF" tabindex="-1" aria-labelledby="generarPDFLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="generarPDFLabel">Generar/Subir PDF</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="pdfForm" enctype="multipart/form-data">
                <input type="hidden" name="id_empleado" id="id_empleado_up">
                <input type="hidden" name="grupo_asignacion" id="grupo_asignacion">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombres" class="form-label">Subir constancia de uniformes de:</label>
                        <input type="text" class="form-control" id="nombres" name="nombres"
                            value="<?= $data['uniformes']['nombres']; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="ubicacion" class="form-label">Seleccionar archivo PDF</label>
                        <input class="form-control" type="file" id="ubicacion" name="ubicacion" accept=".pdf">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url(); ?>/Assets/js/scripts.js"></script>