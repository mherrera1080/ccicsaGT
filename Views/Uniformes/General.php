<?php headerAdmin($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />

<div class="main p-3">
    <main>
        <div style="height: 25px;"></div>

        <div class="container-fluid px-4">
            <div style="height: 25px;"></div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Uniformes General</h4>
                        </div>
                        <div class="card-body">
                            <table id="tableGeneral" class="table table-striped table-bordered" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Camisas</th>
                                        <th>Pantalones</th>
                                        <th>Botas</th>
                                        <th>Fecha Asignacion</th>
                                    </tr>
                                </thead>
                                <tbody id="tableGeneral">
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

<script src="<?= base_url(); ?>/Assets/js/scripts.js"></script>