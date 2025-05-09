<?php headerAdmin($data); ?>
<div class="main p-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>Indicadores de RH</h2>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-chart-area me-2"></i>
                        <h5 class="mb-0">Movimientos de RH</h5>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Selector de año -->
                    <form id="filtroAnio" class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex flex-column">
                            <label for="anio" class="font-weight-bold">Seleccionar Año:</label>
                            <select id="anio" name="anio" class="form-control form-control-sm">
                                <!-- Los años se cargarán dinámicamente aquí -->
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg ms-3">Filtrar <i
                                class="fas fa-filter"></i></button>
                    </form>

                    <!-- Canvas para el gráfico -->
                    <div class="chart-container">
                        <canvas id="rhBAR"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Razones de Baja
                </div>
                <div class="card-body">
                    <form id="filtroMesBajas" class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex flex-column">
                            <select id="mesBaja" name="mesBaja" class="form-control form-control-sm">
                                <option selected disabled value="">Total Año</option>
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filtrar
                            <i class="fas fa-filter"></i></button>
                    </form>
                    <div class="chart-container">
                        <canvas id="bajasPie" style="height: 370px; width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-person-circle-plus"></i>
                    Reclutaciones
                </div>
                <div class="card-body">
                    <form id="filtroReclutamientoMes" class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex flex-column">
                            <select id="mesReclutamiento" name="mesReclutamiento" class="form-control form-control-sm">
                                <option selected disabled value="">Total Año</option>
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filtrar
                            <i class="fas fa-filter"></i></button>
                    </form>
                    <div class="chart-container">
                        <canvas id="reclutamientoDonut" style="height: 370px; width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-person-circle-plus"></i>
                    Areas
                </div>
                <div class="card-body">
                    <form id="areasMes" class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex flex-column">
                            <select id="mesArea" name="mesArea" class="form-control form-control-sm">
                                <option selected disabled value="">Total Año</option>
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filtrar
                            <i class="fas fa-filter"></i></button>
                    </form>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <div class="chart-container" style="flex: 1; min-width: 300px;">
                            <canvas id="areasAltasDonut" style="min-height: 300px; width: 100%;"></canvas>
                        </div>
                        <div class="chart-container" style="flex: 1; min-width: 300px;">
                            <canvas id="areasBajasDonut" style="min-height: 300px; width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-xl-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-chart-area me-2 text-primary"></i>
                        <h2 class="h5 mb-0">Total Expedientes</h2>
                    </div>
                </div>
                <div class="card-body">
                    <form id="filtroColaborador" class="row g-3 align-items-center mb-4">
                        <div class="col-md-6 col-12">
                            <select id="id_empleado" name="id_empleado" class="form-select form-select-sm">
                                <!-- Opciones dinámicas -->
                            </select>
                        </div>
                        <div class="col-md-6 col-12 d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary btn-sm" aria-label="Aplicar filtro">
                                <i class="fa-solid fa-magnifying-glass me-1"></i>buscar
                            </button>
                            <button type="button" id="btnRestaurar" class="btn btn-warning btn-sm"
                                aria-label="Restablecer filtros">
                                <i class="fas fa-undo me-1"></i>Restaurar
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table id="tableDocumentos" class="table table-striped table-hover table-bordered">
                            <caption class="visually-hidden">Listado de documentos</caption>
                            <thead class="table-light">
                                <tr>
                                    <!-- Encabezados -->
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Datos -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <dl></dl>

    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>Indicadores de Vacaciones</h2>
    </div>

    <!-- VACACIONES -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-chart-area me-2"></i>
                        <h5 class="mb-0">Solicitud de Vacaciones</h5>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Selector de año -->
                    <form id="solicitudAño" class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex flex-column">
                            <label for="anio" class="font-weight-bold">Seleccionar Año:</label>
                            <select id="anio_vacaciones" name="anio" class="form-control form-control-sm">
                                <!-- Los años se cargarán dinámicamente aquí -->
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg ms-3">Filtrar <i class="fas fa-filter"></i>
                        </button>
                    </form>
                    <!-- Canvas para el gráfico -->
                    <div class="chart-container">
                        <canvas id="vacacionesBar"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Solicides de Areas
                </div>
                <div class="card-body">
                    <form id="filtroAreaVacaciones" class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex flex-column">
                            <select id="mesVacaciones" name="mesVacaciones" class="form-control form-control-sm">
                                <option selected disabled value="">Total Año</option>
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filtrar
                            <i class="fas fa-filter"></i></button>
                    </form>
                    <div class="chart-container">
                        <canvas id="areasVacaciones" style="height: 370px; width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Jefes con mas Rechazos
                </div>
                <div class="card-body">
                    <form id="filtroRechazoVacaciones" class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex flex-column">
                            <select id="mesRechazos" name="mesRechazos" class="form-control form-control-sm">
                                <option selected disabled value="">Total Año</option>
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filtrar
                            <i class="fas fa-filter"></i></button>
                    </form>
                    <div class="chart-container">
                        <canvas id="rechazosVacaciones" style="height: 370px; width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php footerAdmin($data); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function () {
            $('#id_empleado').select2({
                placeholder: "Buscar empleado...",
                allowClear: true
            });
        });
    </script>
    <style>
        .chart-container {
            position: relative;
            width: 100%;
            height: 300px;
            /* Altura fija */
            min-height: 300px;
            /* Previene colapso al destruir el gráfico */
        }
    </style>