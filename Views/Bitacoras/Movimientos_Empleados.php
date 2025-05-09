<?php headerAdmin($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />

<div class="main p-3">
    <main>
        <div style="height: 25px;"></div>
        <div class="container-fluid px-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Bitacora de Movimientos de Empleados</h4>
            </div>
            <div style="height: 25px;">
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <table id="tableMovimientos" class="table table-striped table-bordered" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Id</th>
                                        <th>Identificacion</th>
                                        <th>Apellidos</th>
                                        <th>Nombres</th>
                                        <th>Puesto Contrato</th>
                                        <th>Puesto Operativo</th>
                                        <th>Jefe Inmediato</th>
                                        <th>Lider de Proceso</th>
                                        <th>Fecha Cambio</th>
                                        <th>Responsable Movimiento</th>
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