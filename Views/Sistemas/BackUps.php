<?php headerAdmin($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />

<div class="main p-3">

    <div style="height: 25px;"></div>
    <div class="row">
        <div class="col-7 col-md-2 col-lg-1 d-flex">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Crear Copia DB
            </button>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Vista previa del respaldo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formBackup">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre_archivo" class="form-label">Nombre del archivo</label>
                                <input type="text" class="form-control" id="nombre_archivo" name="nombre_archivo"
                                    value="backup_recursos_db" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="ruta" class="form-label">Ruta</label>
                                <input type="text" class="form-control" id="ruta" name="ruta" value="Respaldos">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fecha_creacion" class="form-label">Fecha de creación</label>
                                <input type="date" class="form-control" id="fecha_creacion" name="fecha_creacion"
                                    value="<?= date('Y-m-d'); ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="usuario" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="usuario" name="usuario"
                                    value="<?= $data['usuario']['nombre_completo']; ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar respaldo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-4">
        <div style="height: 50px;"></div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Registro de Actividades</h4>
                    </div>
                    <div class="card-body">
                        <table id="TableBackups" class="table table-striped table-bordered" style="width:100%">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Archivo</th>
                                    <th>Peso(MB)</th>
                                    <th>Ruta </th>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
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

<script src="<?= base_url(); ?>/Assets/js/scripts.js"></script>