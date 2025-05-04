<?php
function make_sidebar() {?>
    <!-- IMAGENS -->
<svg xmlns="http://www.w3.org/2000/svg" class="d-none">
    <symbol id="home" viewBox="0 0 16 16">
        <path d="M8 3.293l-6 6V14a1 1 0 0 0 1 1h3v-3h4v3h3a1 1 0 0 0 1-1v-4.707l-6-6z"/>
        <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.647a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/>
    </symbol>
    <symbol id="heart" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>
    </symbol>

    <symbol id="puzzle" viewBox="0 0 16 16">
        <path d="M3.112 3.645A1.5 1.5 0 0 1 4.605 2H7a.5.5 0 0 1 .5.5v.382c0 .696-.497 1.182-.872 1.469a.5.5 0 0 0-.115.118l-.012.025L6.5 4.5v.003l.003.01q.005.015.036.053a.9.9 0 0 0 .27.194C7.09 4.9 7.51 5 8 5c.492 0 .912-.1 1.19-.24a.9.9 0 0 0 .271-.194.2.2 0 0 0 .036-.054l.003-.01v-.008l-.012-.025a.5.5 0 0 0-.115-.118c-.375-.287-.872-.773-.872-1.469V2.5A.5.5 0 0 1 9 2h2.395a1.5 1.5 0 0 1 1.493 1.645L12.645 6.5h.237c.195 0 .42-.147.675-.48.21-.274.528-.52.943-.52.568 0 .947.447 1.154.862C15.877 6.807 16 7.387 16 8s-.123 1.193-.346 1.638c-.207.415-.586.862-1.154.862-.415 0-.733-.246-.943-.52-.255-.333-.48-.48-.675-.48h-.237l.243 2.855A1.5 1.5 0 0 1 11.395 14H9a.5.5 0 0 1-.5-.5v-.382c0-.696.497-1.182.872-1.469a.5.5 0 0 0 .115-.118l.012-.025.001-.006v-.003l-.003-.01a.2.2 0 0 0-.036-.053.9.9 0 0 0-.27-.194C8.91 11.1 8.49 11 8 11s-.912.1-1.19.24a.9.9 0 0 0-.271.194.2.2 0 0 0-.036.054l-.003.01v.002l.001.006.012.025c.016.027.05.068.115.118.375.287.872.773.872 1.469v.382a.5.5 0 0 1-.5.5H4.605a1.5 1.5 0 0 1-1.493-1.645L3.356 9.5h-.238c-.195 0-.42.147-.675.48-.21.274-.528.52-.943.52-.568 0-.947-.447-1.154-.862C.123 9.193 0 8.613 0 8s.123-1.193.346-1.638C.553 5.947.932 5.5 1.5 5.5c.415 0 .733.246.943.52.255.333.48.48.675.48h.238z"/>
    </symbol>
    <symbol id="person" viewBox="0 0 16 16">
        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>

    </symbol>

    <symbol id="doorOpen" viewBox="0 0 16 16">
        <path d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15zM11 2h.5a.5.5 0 0 1 .5.5V15h-1zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1"/>
    </symbol>
</svg>

<h1 class="visually-hidden">Menu lateral</h1>
<aside id="sidebar" class="sidebar position-fixed top-0 start-0 bottom-0 d-none d-md-block" style="width: 250px !important;">
    <div class="d-flex flex-column flex-shrink-0 p-3 menu-lateral h-100" style="width: 280px;">
        <a class="align-items-start pb-3 mb-3 link-body-emphasis text-decoration-none border-bottom">
            <div class="logo-container py-3">
                <img src="assets/logo/Acalento_logo_claro.svg" alt="Logo Acalento Claro" class=" logo logo-tema-claro">
            </div>
            <span class="fs-3 fw-semibold saudacao mt-5 ">Olá, <?= preg_split('/[ ]/', $_SESSION['USER_NAME'])[0] ?></span>
</a>
<!-- sidebar navigation -->
<ul class="sidebar-nav p-0 list-unstyled">
    <li class="sidebar-item mb-1">
        <a  href="index.php?common=6" class="btn btn-toggle  d-inline-flex align-items-center rounded border-0 collapsed">
            <svg class="bi me-2" width="20" height="20" aria-hidden="true">
                <use xlink:href="#home"/>
            </svg>
            Página Inicial
        </a>
    </li>

    <li class="sidebar-item mb-1">
        <a  href="index.php?common=7" class="btn btn-toggle  d-inline-flex align-items-center rounded border-0 collapsed">
            <svg class="bi me-2" width="20" height="20" aria-hidden="true">
                <use xlink:href="#person"/>
            </svg>
            Minha conta
        </a>
    </li>

    <li class="sidebar-item mb-1">
        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"
                data-bs-toggle="collapse" data-bs-target="#eventos-collapse" aria-expanded="false">
            <svg class="bi me-2" width="20" height="20" aria-hidden="true">
                <use xlink:href="#puzzle" >
            </svg>
            Eventos
        </button>
        <div class="collapse" id="eventos-collapse">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                <?php if (!$_SESSION['USER_IS_VOLUNTARY']) { ?>
                    <li><a href="index.php?voluntary=2" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Participe de eventos</a></li>
                <?php } ?>
                <?php if ($_SESSION['USER_IS_ADMINISTRATOR']) { ?>
                    <li><a href="index.php?adm=2" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Cadastrar evento</a></li>
                    <li><a href="index.php?adm=5" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Editar evento</a></li>
                <?php } ?>
                <?php if ($_SESSION['USER_IS_VOLUNTARY']) { ?>
                    <li><a href="index.php?voluntary=2" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Inscrever-se</a></li>
                <?php } ?>
            </ul>
        </div>
    </li>

    <li class="sidebar-item mb-1">
        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#doacoes-collapse" aria-expanded="false">
            <svg class="bi me-2" width="20" height="20" aria-hidden="true">
                <use xlink:href="#heart" />
            </svg>
            Doações
        </button>
        <div class="collapse" id="doacoes-collapse">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                <?php if (!$_SESSION['USER_IS_DONATOR']) { ?>
                    <!--                        <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Faça a sua primeira doação</a></li>-->
                <?php } ?>
                <?php if ($_SESSION['USER_IS_ADMINISTRATOR']) { ?>
                    <li><a href="index.php?adm=1" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Cadastrar campanha</a></li>
                    <li><a href="index.php?adm=8" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Visualizar campanhas</a></li>
                    <li><a href="index.php?adm=3" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Registrar doação</a></li>
                    <li><a href="index.php?adm=7" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Visualizar doações</a></li>
                <?php } ?>
                <?php if ($_SESSION['USER_IS_DONATOR']) { ?>
                    <li><a href="index.php?donator=1" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Suas doações</a></li>
                <?php } ?>
            </ul>
        </div>
    </li>
</ul>
<!-- fim da navegão -->
<div class="sidebar-footer border-top d-flex mt-auto py-2">
    <a  href="index.php?common=3" class="btn btn-toggle  d-inline-flex align-items-center rounded border-0 collapsed">
        <svg class="bi me-2" width="20" height="20" aria-hidden="true">
            <use xlink:href="#doorOpen"/>
        </svg>
        Sair
    </a>
</div>
</div>
<div class="b-example-vr"></div>
</aside>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const originalMenu = document.querySelector(".menu-lateral");
        const offcanvasContent = document.getElementById("offcanvasContent");

        if (originalMenu && offcanvasContent) {
            offcanvasContent.innerHTML = originalMenu.innerHTML;
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php
}

function make_mobile_sidebar()
{?>
    <button class="btn btn-primary d-md-none m-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">&#9776;</button>

    <!-- Menu mobile -->
    <div class="offcanvas offcanvas-top d-md-none" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
        <div class="offcanvas-header justify-content-between align-items-center px-3 pt-3 pb-0 border-bottom-0">
            <span class="fs-3 fw-semibold mb-1">Olá, <?= $_SESSION['USER_NAME']?></span>
            <button type="button" class="btn-close ms-2" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
        </div>
        <div class="offcanvas-body pt-2 px-3" id="offcanvasContent"></div>
    </div>

<?php }
?>

