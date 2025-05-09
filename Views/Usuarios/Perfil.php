<?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
    <h1>Perfil del Usuario</h1>
    <p>ID de Usuario: <?php echo $_SESSION['userData']['id_user']; ?></p>
    <p>Correo Empresarial: <?php echo $_SESSION['userData']['correo_empresarial']; ?></p>
    <p>Rol: <?php echo $_SESSION['userData']['role_id']; ?></p>
<?php else: ?>
    <p>No hay sesión activa.</p>
<?php endif; ?>
