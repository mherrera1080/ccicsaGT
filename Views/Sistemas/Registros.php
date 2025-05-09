<?php headerAdmin($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />

<div class="main p-3">

    <div style="height: 25px;"></div>

    <div class="container-fluid mt-4">

        <div style="height: 50px;"></div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Registro de Actividades</h4>
                    </div>
                    <div class="card-body">
                        <table id="TableRegistros" class="table table-striped table-bordered" style="width:100%">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Cod. Empleado</th>
                                    <th>Empleado</th>
                                    <th>Modulo</th>
                                    <th>Fecha</th>
                                    <th>IP</th>
                                    <th>Hostname</th>
                                    <th>Accion</th>
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