<?php
function make_cards_carousel($next_events)
{ ?>
    <div class="position-relative d-flex justify-content-center align-items-center my-4">

        <!-- Botão anterior -->
        <button class="position-absolute bg-transparent border-0" style="left: -10px; top: 50%; transform: translateY(-50%);" type="button" data-bs-target="#carouselEventos" data-bs-slide="prev">
            <i class="bi bi-chevron-left fs-1" style="color: var(--primary-background);"></i>
            <span class="visually-hidden">Anterior</span>
        </button>

        <!-- Carrossel centralizado com largura menor -->
        <div id="carouselEventos" class="carousel slide" data-bs-ride="carousel" style="width: 95%">
            <div class="carousel-inner">
                <?php
                $first = true;
                while ($evento = $next_events->fetch_object()) {
                    echo '<div class="carousel-item ' . ($first ? 'active' : '') . '">';
                    render_one_event_card($evento, voluntary: true, horizontal: true);
                    echo '</div>';
                    $first = false;
                }
                ?>
            </div>
        </div>

        <!-- Botão próximo -->
        <button class="position-absolute bg-transparent border-0" style="right: -10px; top: 50%; transform: translateY(-50%);" type="button" data-bs-target="#carouselEventos" data-bs-slide="next">
            <i class="bi bi-chevron-right fs-1" style="color: var(--primary-background);"></i>
            <span class="visually-hidden">Próximo</span>
        </button>

    </div>
<?php }
