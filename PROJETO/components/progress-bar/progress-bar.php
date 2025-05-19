<?php
function render_progress_bar(int $etapa) { ?>
    <div class="d-flex align-items-center justify-content-between w-100 progress-wizard">

        <!-- Etapa 1 -->
        <div class="step <?= $etapa >= 1 ? 'active' : '' ?>">
            <div class="circle">1</div>
            <div class="label">Cadastro</div>
            <div class="line"></div>
        </div>

        <!-- Etapa 2 -->
        <div class="step <?= $etapa >= 2 ? 'active' : '' ?>">
            <div class="circle">2</div>
            <div class="label">Doação</div>
            <div class="line"></div>
        </div>

        <!-- Etapa 3 -->
        <div class="step <?= $etapa >= 3 ? 'active' : '' ?>">
            <div class="circle">3</div>
            <div class="label">Confirmação</div>
        </div>

    </div>
<?php
}
