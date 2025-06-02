<?php
include_once (ROOT . "/models/donator_models_php.php");

function donator_badges($total_donations) {
    if ($total_donations <= 5) { ?>
        <div class="badge-card badge-primary-donation">
            <p>🌱 Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Doador Semeador</strong></p>
            <p>Você já realizou <?= $total_donations ?> doações</p>
        </div>
    <?php } elseif ($total_donations <= 10) { ?>
        <div class="badge-card badge-secondary-donation">
            <p>🌷 Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Doador Cultivador</strong></p>
            <p>Você já realizou <?= $total_donations ?> doações</p>
        </div>
    <?php } elseif ($total_donations <= 15) { ?>
        <div class="badge-card badge-tertiary-donation">
            <p>🌟 Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Doador Estrela Solidária</strong></p>
            <p>Você já realizou <?= $total_donations ?> doações</p>
        </div>
    <?php } elseif ($total_donations <= 20) {?>
        <div class="badge-card badge-quaternary-donation">
            <p>🎖️ Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Doador Supremo!!</strong></p>
            <p>Você já realizou <?= $total_donations ?> doações</p>
        </div>
    <?php } else { ?>
        <div class="badge-card badge-final">
            <p>🏆 Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Doador Lendário!!</strong></p>
            <p>Você já realizou <?= $total_donations ?> doações</p>
        </div>
    <?php }
}

function voluntary_badge($total_events) {
    if ($total_events <= 5) { ?>
        <div class="badge-card badge-primary-voluntary">
            <p>🧤 Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Voluntário Mãos Amigas</strong></p>
            <p>Você já participou de <?= $total_events ?> eventos</p>
        </div>
    <?php } elseif ($total_events <= 10) { ?>
        <div class="badge-card badge-secondary-voluntary">
            <p>🪴 Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Voluntário Cuidador de Raízes</strong></p>
            <p>Você já participou de <?= $total_events ?> eventos</p>
        </div>
    <?php } elseif ($total_events <= 15) { ?>
        <div class="badge-card badge-tertiary-voluntary">
            <p>📍 Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Voluntário Presença Certa</strong></p>
            <p>Você já participou de <?= $total_events ?> eventos</p>
        </div>
    <?php } elseif ($total_events <= 20) {?>
        <div class="badge-card badge-quaternary-voluntary">
            <p>🦋 Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Voluntário Guardião do Acalento!!</strong></p>
            <p>Você já participou de <?= $total_events ?> eventos</p>
        </div>
    <?php } else { ?>
        <div class="badge-card badge-final">
            <p>🏆 Parabéns <?= $_SESSION['USER_NAME'] ?>! Você é um <strong>Voluntário Lendário!!</strong></p>
            <p>Você já participou de <?= $total_events ?> eventos</p>
        </div>
    <?php }
}

function last_donation_bagde($days)
{
    if ($days >= 30) { ?>
        <div class="badge-card badge-pattern d-flex ">
            <p>Faz <?= $days ?> dias desde a sua última doação.</p>
            <a href="#">Que tal fazer uma nova doação hoje?</a>
        </div>
    <?php }
}

function last_events_badge($days)
{
    if ($days >= 90) { ?>
        <div class="badge-card badge-pattern d-flex ">
            <p>Faz <?= $days ?> dias desde a sua última participação em um evento.</p>
            <a href="index.php?voluntary=2">Que tal se inscrever em um evento hoje?</a>
        </div>
<?php }
}

function make_total_donations_badge($total_doacoes_mes) {
    if ($total_doacoes_mes > 0) { ?>
        <div class="badge-circular badge-alerta">
            <p class="mb-0">📦</p>
            <strong class="mt-1"><?= $total_doacoes_mes ?> doações</strong>
            <small class="badge-variacao">No total deste mês</small>
        </div>
    <?php } else { ?>
        <div class="badge-circular badge-perigo">
            <p class="mb-0">📭</p>
            <strong class="mt-1">0 doações</strong>
            <small class="badge-variacao">Ainda não recebemos doações</small>
        </div>
    <?php }
}

function make_monetary_donations_badge($total_atual, $total_passado) {
    $diferenca = $total_atual - $total_passado;
    $porcentagem = $total_passado > 0 ? ($diferenca / $total_passado) * 100 : 0;

    if ($diferenca > 0) {
        $classe = 'badge-sucesso';
        $texto = "⬆️ Aumento de " . number_format($porcentagem, 1, ',', '.') . "%";
    } elseif ($diferenca < 0) {
        $classe = 'badge-perigo';
        $texto = "⬇️ Queda de " . number_format(abs($porcentagem), 1, ',', '.') . "%";
    } else {
        $classe = 'badge-alerta';
        $texto = "➡️ Mesmo valor do mês passado";
    }
    ?>
    <div class="badge-circular <?= $classe ?>">
        <p class="mb-0">💰</p>
        <strong class="mt-1">R$ <?= number_format($total_atual, 2, ',', '.') ?></strong>
        <small class="badge-variacao"><?= $texto ?></small>
    </div>
    <?php
}

function make_material_donations_badge($materiais_mes_atual, $materiais_mes_passado) {
    $diferenca = $materiais_mes_atual - $materiais_mes_passado;
    $porcentagem = $materiais_mes_passado > 0 ? ($diferenca / $materiais_mes_passado) * 100 : 0;

    if ($diferenca > 0) {
        $classe = 'badge-sucesso';
        $texto = "⬆️ Aumento de " . number_format($porcentagem, 1, ',', '.') . "%";
    } elseif ($diferenca < 0) {
        $classe = 'badge-perigo';
        $texto = "⬇️ Queda de " . number_format(abs($porcentagem), 1, ',', '.') . "%";
    } else {
        $classe = 'badge-alerta';
        $texto = "➡️ Mesmo número do mês passado";
    }
    ?>
    <div class="badge-circular <?= $classe ?>">
        <p class="mb-0">🎁</p>
        <strong class="mt-1"><?= $materiais_mes_atual ?> itens</strong>
        <small class="badge-variacao"><?= $texto ?></small>
    </div>
    <?php
}
