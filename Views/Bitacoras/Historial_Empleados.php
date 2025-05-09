<?php headerAdmin($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />

<div class="main p-3">
    <main>
        <div style="height: 25px;"></div>
        <div class="container-fluid px-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Historial de Empleados</h4>
            </div>
            <div style="height: 25px;">

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <table id="tableHistorial" class="table table-striped table-bordered nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Apellido</th>
                                        <th>Nombres</th>
                                        <th>Fecha Ingreso</th>
                                        <th>Salario Inicial</th>
                                        <th>Puesto Contrato Inicio</th>
                                        <th>Puesto Operativo Inicio</th>
                                        <th>Departamento/Area Inicio</th>
                                        <th>Fecha Baja Sistema</th>
                                        <th>Fecha Salida (Ultimo Dia Laboral)</th>
                                        <th>Salario Final</th>
                                        <th>Puesto Contrato Final</th>
                                        <th>Puesto Operativo Final</th>
                                        <th>Departamento/Area Final</th>
                                        <th>Caso</th>
                                        <th>Razon Baja</th>
                                        <th>Motivo Baja</th>
                                        <th>Observaciones</th>
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