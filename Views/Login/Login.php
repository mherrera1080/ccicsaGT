<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['page_title']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
</head>
<body>
    <div class="login-container">
        <h2>Bienvenido</h2>
        <!-- Formulario de Login -->
        <form class="login-form" name="formLogin" id="formLogin">
            <div class="input-group">
                <label for="correo_empresarial">Correo electrónico</label>
                <i class="fas fa-envelope"></i>
                <input type="email" name="correo_empresarial" id="correo_empresarial" placeholder="tucorreo@empresa.com"
                    required>
            </div>
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="••••••••" required>
                <i class="fas fa-eye password-toggle" id="togglePassword"></i>
            </div>
            <div class="divider"><span>o</span></div>
            <button type="submit" id="infoSubmitBtn"  >Ingresar <i class="fas fa-arrow-right"></i>
                <span id="infoSpinner" class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
            </button>
        </form>
    </div>

    <div id="modalEspera" class="modal fade" tabindex="-1" data-bs-backdrop="static" aria-labelledby="modalEsperaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEsperaLabel">Verificación de Token</h5>
                </div>
                <div class="modal-body text-center">
                    <form id="tokenForm">
                        <input type="hidden" id="correoToken" name="correo_empresarial">
                        <input type="hidden" id="passwordToken" name="password">
                        <p>Hemos enviado un token a tu correo. Ingresa el código recibido para continuar.</p>
                        <input type="text" id="token" name="token" class="form-control text-center my-3"
                            placeholder="Código de verificación" required>
                        <div class="modal-footer">
                            <button type="submit" id="tokenSubmitBtn" class="btn btn-primary btn-validar">
                                Validar
                                <span id="tokenSpinner" class="spinner-border spinner-border-sm d-none"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="<?= base_url(); ?>/Assets/js/functions_login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const base_url = "<?= base_url(); ?>";
    </script>
</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>