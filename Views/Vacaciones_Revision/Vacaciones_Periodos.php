<?php headerAdmin($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/info.css" />

<div class="main p-3">
    <div class="spacer-25"></div>

    <div class="row">
        <form class="row g-3 align-items-end d-flex flex-wrap" id="formFiltrar">
            <!-- Selección de empleado -->
            <div class="col-12 col-md-4 col-lg-3">
                <label for="id_empleado" class="form-label">Empleado</label>
                <select class="form-control " data-live-search="true" id="id_empleado"
                    name="id_empleado" required title="Seleccione un Empleado">
                </select>
            </div>
            <div class="col-6 col-md-2 col-lg-1 d-flex">
                <button class="btn btn-primary w-100 flex-grow-1" type="submit">
                    Buscar
                </button>
            </div>

            <div class="col-6 col-md-2 col-lg-1 d-flex">
                <button type="button" class="btn btn-warning w-100 flex-grow-1" onclick="location.reload();">
                    Recargar
                </button>
            </div>
        </form>
    </div>


    <div class="spacer-50"></div>

    <div class="row">
        <div class="container-fluid mt-4">
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
                                        <th>ID Empleado</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Dias Totales</th>
                                        <th>Días Consumidos</th>
                                        <th>Días Disponibles</th>
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
</div>

<script src="<?= base_url(); ?>/Assets/js/scripts.js"></script>
<?php footerAdmin($data); ?>
</div>

<style>
    /* Estilos para el contenedor de Select2 */
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.5;
        /* Corregí el valor de line-height */
        border: 1px solid #ced4da;
        border-radius: 4px;
        background-color: #fff;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        width: 400px;
        /* Ancho fijo */
        max-width: 100%;
        /* Ancho máximo */
    }

    /* Estilos cuando el Select2 está enfocado */
    .select2-container--default .select2-selection--single:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Estilos para la flecha del dropdown */
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
        right: 45px;
    }

    /* Estilos para el dropdown */
    .select2-container--default .select2-dropdown {
        border: 1px solid #ced4da;
        border-radius: 4px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Estilos para los elementos del dropdown */
    .select2-container--default .select2-results__option {
        padding: 8px 12px;
        font-size: 14px;
    }

    /* Estilos para el elemento seleccionado en el dropdown */
    .select2-container--default .select2-results__option--highlighted {
        background-color: #007bff;
        color: #fff;
    }

    /* Estilos para el placeholder */
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d;
    }
</style>