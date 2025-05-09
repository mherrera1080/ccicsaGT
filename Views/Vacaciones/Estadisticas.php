<?php headerVacaciones($data); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/styles.css" />
<link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/info.css" />

<div class="main p-3">

    <div class="profile-section mb-4">
        <div class="profile-wrapper">
            <div class="profile-photo">
                <img src="<?= base_url(); ?>/Assets/images/Perfiles/<?= $data['usuario']['identificacion'] ?? 'usuario.png' ?>"
                    alt="Foto de perfil" class="profile-img">
            </div>

            <div class="profile-info">
                <h2 class="profile-name"><?= $data['usuario']['nombre_completo'] ?></h2>
                <div class="profile-details">
                <div class="detail-item">
                        <span class="detail-label">Puesto:</span>
                        <span class="detail-value"><?= $data['usuario']['puesto'] ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Departamento:</span>
                        <span class="detail-value"><?= $data['usuario']['departamento_laboral'] ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Area:</span>
                        <span class="detail-value"><?= $data['usuario']['area_laboral'] ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Fecha de ingreso:</span>
                        <span
                            class="detail-value"><?= date('d/m/Y', strtotime($data['usuario']['fecha_ingreso'])) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Formulario:</span>
                        <span class="detail-value"><?= $data['usuario']['formulario_vacaciones'] ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="stats-container">
        <!-- Tarjeta de días disponibles -->
        <div class="stat-card bg-light">
            <h3 class="stat-title">Días de Vacaciones Disponibles</h3>
            <div class="stat-value text-primary"><?= $data['estadistica']['dias_disponibles'] ?></div>
            <div class="stat-info">Días disponibles para usar</div>
        </div>

        <!-- Tarjeta de días utilizados -->
        <div class="stat-card bg-light">
            <h3 class="stat-title">Días Utilizados</h3>
            <div class="stat-value text-success"><?= $data['estadistica']['dias_consumidos'] ?></div>
            <div class="stat-info">Días disfrutados este año</div>
        </div>

        <!-- Tarjeta de solicitudes pendientes -->
        <div class="stat-card bg-light">
            <h3 class="stat-title">Solicitudes Pendientes</h3>
            <div class="stat-value text-warning"><?= $data['estadistica']['solicitudes_pendientes'] ?></div>
            <div class="stat-info">En proceso de aprobación</div>
        </div>

        <!-- Tarjeta de historial aprobado -->
        <div class="stat-card bg-light">
            <h3 class="stat-title">Solicitudes Revisadas</h3>
            <div class="stat-value text-info"><?= $data['estadistica']['solicitudes_revisadas'] ?></div>
            <div class="stat-info">Total este año</div>
        </div>
    </div>

</div>

<?php footerAdmin($data); ?>
<script src="<?= base_url(); ?>/Assets/js/scripts.js"></script>

<style>
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }

    .stat-title {
        font-size: 1rem;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .stat-info {
        color: #888;
        font-size: 0.9rem;
    }

    .requests-table {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
    }

    /* Estilos para la sección de perfil */
    .profile-section {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
    }

    .profile-wrapper {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .profile-photo {
        flex: 0 0 120px;
    }

    .profile-img {
        width: 150px;
        height: 150px;
        border-radius: 12%;
        object-fit: cover;
        border: 3px solid #eaeaea;
    }

    .profile-info {
        flex: 1;
    }

    .profile-name {
        color: #2c3e50;
        margin-bottom: 1rem;
        font-size: 1.8rem;
    }

    .profile-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .detail-item {
        padding: 0.5rem;
        background: #f8f9fa;
        border-radius: 6px;
    }

    .detail-label {
        display: block;
        color: #7f8c8d;
        font-size: 0.9rem;
    }

    .detail-value {
        display: block;
        color: #2c3e50;
        font-weight: 500;
        margin-top: 0.3rem;
    }

    /* Responsive para móviles */
    @media (max-width: 768px) {
        .profile-wrapper {
            flex-direction: column;
            text-align: center;
        }

        .profile-details {
            grid-template-columns: 1fr;
        }
    }
</style>