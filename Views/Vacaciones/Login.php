<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
</head>

<body>
    <div class="main p-3">
        <!-- Modal para ingresar correo -->
        <div class="modal fade" id="emailModal" aria-hidden="true" aria-labelledby="emailModalLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="emailModalLabel">Ingresar Correo Empresarial</h5>
                    </div>
                    <div class="modal-body">
                        <form id="emailForm">
                            <div class="mb-3">
                                <label for="correo_empresarial" class="form-label">Correo Empresarial</label>
                                <input type="email" class="form-control" id="correo_empresarial"
                                    name="correo_empresarial" placeholder="Ingresa tu correo" required>
                            </div>
                            <button type="submit" id="emailSubmitBtn" class="btn btn-primary w-100">
                                Enviar Código
                                <span id="emailSpinner" class="spinner-border spinner-border-sm d-none"
                                    aria-hidden="true"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para ingresar token -->
        <div class="modal fade" id="tokenModal" aria-hidden="true" aria-labelledby="tokenModalLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tokenModalLabel">Ingresar Código de Verificación</h5>
                    </div>
                    <div class="modal-body">
                        <form id="tokenForm">
                            <input type="hidden" id="correoToken" name="correo_empresarial">
                            <!-- Input oculto agregado -->
                            <div class="mb-3">
                                <label for="token" class="form-label">Código de Verificación</label>
                                <input type="text" class="form-control" id="token" name="token"
                                    placeholder="Ingresa el código recibido" required>
                            </div>
                            <button type="submit" id="tokenSubmitBtn" class="btn btn-success w-100">
                                Validar Código
                                <span id="tokenSpinner" class="spinner-border spinner-border-sm d-none"
                                    aria-hidden="true"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        const base_url = "<?= base_url(); ?>";
    </script>
</body>
<script src="<?= media(); ?>/js/<?= $data['page_functions_js']; ?>"></script>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>