<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/default.css">
    <link href="css/form-style.css" rel="stylesheet">
    <link rel="stylesheet" href="css/sidebars.css">
    <title>Title</title>
</head>
<body>

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
    <symbol id="pessoa" viewBox="0 0 16 16">
        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>

    </symbol>
</svg>

<h1 class="visually-hidden">Menu lateral</h1>
<aside id="sidebar" class="sidebar position-fixed top-0 start-0 bottom-0 d-none d-md-block" style="width: 250px !important;">
    <div class="flex-shrink-0 p-3 menu-lateral h-100" style="width: 280px;">
        <a class="d-flex flex-column align-items-start pb-3 mb-3 link-body-emphasis text-decoration-none border-bottom">
            <div class="logo-container">
                <img src="assets/logo/Acalento_logo_claro.svg" alt="Logo Acalento Claro" class=" logo logo-tema-claro">
            </div>
            <span class="fs-3 fw-semibold saudacao mt-5">Olá, <?= $_SESSION['USER_NAME']?></span>
        </a>
        <!-- sidebar navigation -->
        <ul class="sidebar-nav p-0 list-unstyled">
            <li class="sidebar-item mb-1">
                <a  href="index.html" class="btn btn-toggle  d-inline-flex align-items-center rounded border-0 collapsed"
                        data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    <svg class="bi me-2" width="20" height="20" aria-hidden="true">
                        <use xlink:href="#pessoa"/>
                    </svg>
                    Página Inicial
                </a>
            </li>

            <li class="mb-1">
                <button class="btn btn-toggle  d-inline-flex align-items-center rounded border-0 collapsed"
                        data-bs-toggle="collapse" data-bs-target="#pessoa-collapse" aria-expanded="false">
                    <svg class="bi me-2" width="20" height="20" aria-hidden="true">
                        <use xlink:href="#home"/>
                    </svg>
                    Minha Conta
                </button>
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
                        <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Participe de eventos</a></li>
                        <li><a href=php/pages/createEvent.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Cadastrar evento</a></li>
                        <li><a href="../../php/pages/editEvent.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Editar evento</a></li>
                        <li><a href="../../php/pages/viewEvent.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Visualizar</a></li>
                                        <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Inscrever-se</a></li>
                                        <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Eventos inscritos</a></li>
                                        <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Confirmar presença</a></li>
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
                                        <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Faça a sua primeira doação</a></li>
                        <li><a href="../../php/pages/createDonation.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Cadastrar campanha</a></li>
                        <li><a href="../../php/pages/viewCampaign.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Editar campanha</a></li>
                        <li><a href="../../php/pages/createItem.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Cadastrar item</a></li>
                        <li><a href="../../php/pages/viewAllDonation.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Visualizar doações</a></li>
                                        <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Nova doação</a></li>
                                        <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Suas doações</a></li>
                    </ul>
                </div>
            </li>
        </ul>
        <!-- fim da navegão -->
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
</body>
</html>

<!-- voluntário -->
<!--<ul class="sidebar-nav p-0 list-unstyled">-->
<!--    <li class="sidebar-item mb-1">-->
<!--        <button class="btn btn-toggle  d-inline-flex align-items-center rounded border-0 collapsed"-->
<!--                data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">-->
<!--            <svg class="bi me-2" width="20" height="20" aria-hidden="true">-->
<!--                <use xlink:href="#home"/>-->
<!--            </svg>-->
<!--            Página Inicial-->
<!--        </button>-->
<!--    </li>-->
<!---->
<!--    <li class="mb-1">-->
<!--        <button class="btn btn-toggle  d-inline-flex align-items-center rounded border-0 collapsed"-->
<!--                data-bs-toggle="collapse" data-bs-target="#pessoa-collapse" aria-expanded="false">-->
<!--            <svg class="bi me-2" width="20" height="20" aria-hidden="true">-->
<!--                <use xlink:href="#pessoa"/>-->
<!--            </svg>-->
<!--            Minha Conta-->
<!--        </button>-->
<!--    </li>-->
<!---->
<!--    <li class="sidebar-item mb-1">-->
<!--        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"-->
<!--                data-bs-toggle="collapse" data-bs-target="#eventos-collapse" aria-expanded="false">-->
<!--            <svg class="bi me-2" width="20" height="20" aria-hidden="true">-->
<!--                <use xlink:href="#puzzle" >-->
<!--            </svg>-->
<!--            Eventos-->
<!--        </button>-->
<!--        <div class="collapse" id="eventos-collapse">-->
<!--            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">-->

<!--            </ul>-->
<!--        </div>-->
<!--    </li>-->
<!---->
<!--    <li class="sidebar-item mb-1">-->
<!--        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#doacoes-collapse" aria-expanded="false">-->
<!--            <svg class="bi me-2" width="20" height="20" aria-hidden="true">-->
<!--                <use xlink:href="#heart" />-->
<!--            </svg>-->
<!--            Doações-->
<!--        </button>-->
<!--        <div class="collapse" id="doacoes-collapse">-->
<!--            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">-->
<!--                <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Faça a sua primeira doação!!!</a></li>-->
<!--            </ul>-->
<!--        </div>-->
<!--    </li>-->
<!--</ul>-->
<!---->
<!-- voluntário -->
<!--<ul class="sidebar-nav p-0 list-unstyled">-->
<!--    <li class="sidebar-item mb-1">-->
<!--        <button class="btn btn-toggle  d-inline-flex align-items-center rounded border-0 collapsed"-->
<!--                data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">-->
<!--            <svg class="bi me-2" width="20" height="20" aria-hidden="true">-->
<!--                <use xlink:href="#home"/>-->
<!--            </svg>-->
<!--            Página Inicial-->
<!--        </button>-->
<!--    </li>-->
<!---->
<!--    <li class="mb-1">-->
<!--        <button class="btn btn-toggle  d-inline-flex align-items-center rounded border-0 collapsed"-->
<!--                data-bs-toggle="collapse" data-bs-target="#pessoa-collapse" aria-expanded="false">-->
<!--            <svg class="bi me-2" width="20" height="20" aria-hidden="true">-->
<!--                <use xlink:href="#pessoa"/>-->
<!--            </svg>-->
<!--            Minha Conta-->
<!--        </button>-->
<!--    </li>-->
<!---->
<!--    <li class="sidebar-item mb-1">-->
<!--        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"-->
<!--                data-bs-toggle="collapse" data-bs-target="#eventos-collapse" aria-expanded="false">-->
<!--            <svg class="bi me-2" width="20" height="20" aria-hidden="true">-->
<!--                <use xlink:href="#puzzle" >-->
<!--            </svg>-->
<!--            Eventos-->
<!--        </button>-->
<!--        <div class="collapse" id="eventos-collapse">-->
<!--            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">-->
<!--                <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Inscrever-se</a></li>-->
<!--                <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Eventos inscritos</a></li>-->
<!--                <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Confirmar presença</a></li>-->
<!--            </ul>-->
<!--        </div>-->
<!--    </li>-->
<!---->
<!--    <li class="sidebar-item mb-1">-->
<!--        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#doacoes-collapse" aria-expanded="false">-->
<!--            <svg class="bi me-2" width="20" height="20" aria-hidden="true">-->
<!--                <use xlink:href="#heart" />-->
<!--            </svg>-->
<!--            Doações-->
<!--        </button>-->
<!--        <div class="collapse" id="doacoes-collapse">-->
<!--            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">-->
<!--                <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Faça a sua primeira doação!!!</a></li>-->
<!--            </ul>-->
<!--        </div>-->
<!--    </li>-->
<!--</ul>-->
<!---->
<!-- doador -->
<!--<ul class="sidebar-nav p-0 list-unstyled">-->
<!--    <li class="sidebar-item mb-1">-->
<!--        <button class="btn btn-toggle  d-inline-flex align-items-center rounded border-0 collapsed"-->
<!--                data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">-->
<!--            <svg class="bi me-2" width="20" height="20" aria-hidden="true">-->
<!--                <use xlink:href="#home"/>-->
<!--            </svg>-->
<!--            Página Inicial-->
<!--        </button>-->
<!--    </li>-->
<!---->
<!--    <li class="mb-1">-->
<!--        <button class="btn btn-toggle  d-inline-flex align-items-center rounded border-0 collapsed"-->
<!--                data-bs-toggle="collapse" data-bs-target="#pessoa-collapse" aria-expanded="false">-->
<!--            <svg class="bi me-2" width="20" height="20" aria-hidden="true">-->
<!--                <use xlink:href="#pessoa"/>-->
<!--            </svg>-->
<!--            Minha Conta-->
<!--        </button>-->
<!--    </li>-->
<!---->
<!--    <li class="sidebar-item mb-1">-->
<!--        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"-->
<!--                data-bs-toggle="collapse" data-bs-target="#eventos-collapse" aria-expanded="false">-->
<!--            <svg class="bi me-2" width="20" height="20" aria-hidden="true">-->
<!--                <use xlink:href="#puzzle" >-->
<!--            </svg>-->
<!--            Eventos-->
<!--        </button>-->
<!--        <div class="collapse" id="eventos-collapse">-->
<!--            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">-->
<!--                <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Inscrever-se</a></li>-->
<!--            </ul>-->
<!--        </div>-->
<!--    </li>-->
<!---->
<!--    <li class="sidebar-item mb-1">-->
<!--        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#doacoes-collapse" aria-expanded="false">-->
<!--            <svg class="bi me-2" width="20" height="20" aria-hidden="true">-->
<!--                <use xlink:href="#heart"/>-->
<!--            </svg>-->
<!--            Doações-->
<!--        </button>-->
<!--        <div class="collapse" id="doacoes-collapse">-->
<!--            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">-->

<!--            </ul>-->
<!--        </div>-->
<!--    </li>-->
<!--</ul>-->
<!---->
<!-- doador e voluntario -->
<!--<ul class="sidebar-nav p-0 list-unstyled">-->
<!--    <li class="sidebar-item mb-1">-->
<!--        <button class="btn btn-toggle  d-inline-flex align-items-center rounded border-0 collapsed"-->
<!--                data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">-->
<!--            <svg class="bi me-2" width="20" height="20" aria-hidden="true">-->
<!--                <use xlink:href="#home"/>-->
<!--            </svg>-->
<!--            Página Inicial-->
<!--        </button>-->
<!--    </li>-->
<!---->
<!--    <li class="mb-1">-->
<!--        <button class="btn btn-toggle  d-inline-flex align-items-center rounded border-0 collapsed"-->
<!--                data-bs-toggle="collapse" data-bs-target="#pessoa-collapse" aria-expanded="false">-->
<!--            <svg class="bi me-2" width="20" height="20" aria-hidden="true">-->
<!--                <use xlink:href="#pessoa"/>-->
<!--            </svg>-->
<!--            Minha Conta-->
<!--        </button>-->
<!--    </li>-->
<!---->
<!--    <li class="sidebar-item mb-1">-->
<!--        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"-->
<!--                data-bs-toggle="collapse" data-bs-target="#eventos-collapse" aria-expanded="false">-->
<!--            <svg class="bi me-2" width="20" height="20" aria-hidden="true">-->
<!--                <use xlink:href="#puzzle" >-->
<!--            </svg>-->
<!--            Eventos-->
<!--        </button>-->
<!--        <div class="collapse" id="eventos-collapse">-->
<!--            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">-->
<!--                <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Inscrever-se</a></li>-->
<!--                <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Eventos inscritos</a></li>-->
<!--                <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Confirmar presença</a></li>-->
<!--            </ul>-->
<!--        </div>-->
<!--    </li>-->
<!---->
<!--    <li class="sidebar-item mb-1">-->
<!--        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#doacoes-collapse" aria-expanded="false">-->
<!--            <svg class="bi me-2" width="20" height="20" aria-hidden="true">-->
<!--                <use xlink:href="#heart" />-->
<!--            </svg>-->
<!--            Doações-->
<!--        </button>-->
<!--        <div class="collapse" id="doacoes-collapse">-->
<!--            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">-->
<!--                <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Faça uma nova doação</a></li>-->
<!--                <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Suas doações</a></li>-->
<!--            </ul>-->
<!--        </div>-->
<!--    </li>-->
<!--</ul>-->

