<?php
const PAGE_ERROR = [
    1=> "Não possui acesso administrador",
    2=> "Página não existente",
];

const PAGE_SUCCESS = [
    1=> "XXXXXX",
];

function showError($pageNum)
{
    ?>
    <div class="toast-container position-absolute p-3 top-0 start-50 translate-middle-x">
        <div class="toast text-bg-danger border-0 show" id="toastRuim">
            <div class="d-flex">
                <div class="toast-body">
                    <?= isset(PAGE_ERROR[$pageNum]) ? PAGE_ERROR[$pageNum] : "Acão inexistente" ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
            </div>
        </div>
    </div>
    <?php
}

function showSucess($pageNum)
{
    ?>
    <div class="toast-container position-absolute p-3 top-0 start-50 translate-middle-x">
        <div class="toast text-bg-success border-0 show" id="toastBom">
            <div class="d-flex">
                <div class="toast-body">
                    <?= isset(PAGE_SUCCESS[$pageNum]) ? PAGE_SUCCESS[$pageNum] : "Acão inexistente" ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
            </div>
        </div>
    </div>
    <?php
}