<?php
include_once (ROOT . "/models/donator_models_php.php");

function donator_badges($total_donations) {
    if ($total_donations <= 5) { ?>
        <div class="badge badge-primary-donation">
            <p>🌱 Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Doador Semeador</strong></p>
            <p>Você já realizou <?= $total_donations ?> doações</p>
        </div>
    <?php } elseif ($total_donations <= 10) { ?>
        <div class="badge badge-secondary-donation">
            <p>🌷 Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Doador Cultivador</strong></p>
            <p>Você já realizou <?= $total_donations ?> doações</p>
        </div>
    <?php } elseif ($total_donations <= 15) { ?>
        <div class="badge badge-tertiary-donation">
            <p>🌟 Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Doador Estrela Solidária</strong></p>
            <p>Você já realizou <?= $total_donations ?> doações</p>
        </div>
    <?php } elseif ($total_donations <= 20) {?>
        <div class="badge badge-quaternary-donation">
            <p>🎖️ Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Doador Supremo!!</strong></p>
            <p>Você já realizou <?= $total_donations ?> doações</p>
        </div>
    <?php } else { ?>
        <div class="badge badge-final">
            <p>🏆 Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Doador Lendário!!</strong></p>
            <p>Você já realizou <?= $total_donations ?> doações</p>
        </div>
    <?php }
}

function voluntary_badge($total_events) {
    if ($total_events <= 5) { ?>
        <div class="badge badge-primary-voluntary">
            <p>🧤 Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Voluntário Mãos Amigas</strong></p>
            <p>Você já participou de <?= $total_events ?> eventos</p>
        </div>
    <?php } elseif ($total_events <= 10) { ?>
        <div class="badge badge-secondary-voluntary">
            <p>🪴 Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Voluntário Cuidador de Raízes</strong></p>
            <p>Você já participou de <?= $total_events ?> eventos</p>
        </div>
    <?php } elseif ($total_events <= 15) { ?>
        <div class="badge badge-tertiary-voluntary">
            <p>📍 Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Voluntário Presença Certa</strong></p>
            <p>Você já participou de <?= $total_events ?> eventos</p>
        </div>
    <?php } elseif ($total_events <= 20) {?>
        <div class="badge badge-quaternary-voluntary">
            <p>🦋 Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Voluntário Guardião do Acalento!!</strong></p>
            <p>Você já participou de <?= $total_events ?> eventos</p>
        </div>
    <?php } else { ?>
        <div class="badge badge-final">
            <p>🏆 Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Voluntário Lendário!!</strong></p>
            <p>Você já participou de <?= $total_events ?> eventos</p>
        </div>
    <?php }
}

function last_donation_bagde($days)
{
    if ($days >= 30) { ?>
        <div class="badge badge-pattern d-flex ">
            <p>Faz <?= $days ?> dias desde a sua última doação.</p>
            <a href="#">Que tal fazer uma nova doação hoje?</a>
        </div>
    <?php }
}

function last_events_badge($days)
{
    if ($days >= 90) { ?>
        <div class="badge badge-pattern d-flex ">
            <p>Faz <?= $days ?> dias desde a sua última participação em um evento.</p>
            <a href="index.php?voluntary=2">Que tal se inscrever em um evento hoje?</a>
        </div>
<?php }
}

