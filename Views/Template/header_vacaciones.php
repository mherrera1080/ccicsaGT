<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?= $data['page_title']; ?></title>

    <!-- CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?= media(); ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <!-- Font Awesome y LineIcons -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.lineicons.com/5.0/lineicons.css" rel="stylesheet" />

    <!-- Otros estilos -->
    <link rel="stylesheet" href="<?= media(); ?>/plugins/daterangepicker/daterangepicker.css">
    <link href="Assets/css/info.css" rel="stylesheet">
    <link href="Assets/css/styles.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <hr>
            <div class="d-flex mb-4">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-user-multiple-4"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="<?= base_url(); ?>/Vacaciones/Solicitud">C.C. RECURSOS HUMANOS</a>
                </div>
            </div>
            <hr>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="<?= base_url(); ?>/Vacaciones/Solicitud" class="sidebar-link">
                        <i class="lni lni-target-user"></i>
                        <span>Solicitar Vacaciones</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="<?= base_url(); ?>/Vacaciones/Estadisticas" class="sidebar-link">
                        <i class="lni lni-books-2"></i>
                        <span> Mis Estadisticas</span>
                    </a>
                </li>

            </ul>
            <div class="sidebar-footer">
                <a href="#" id="logoutButtonOperativa" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>