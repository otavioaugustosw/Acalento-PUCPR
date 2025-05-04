<?php
function make_green_popup($message)
{?>
    <div class="toast-container position-absolute p-3 top-0 start-50 translate-middle-x">
        <div class="toast text-bg-success border-0 show" id="toastBom">
            <div class="d-flex">
                <div class="toast-body">
                    <?=$message?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
            </div>
        </div>
    </div>
<?php }

function make_red_popup($message)
{?>
    <div class="toast-container position-absolute p-3 top-0 start-50 translate-middle-x">
        <div class="toast text-bg-danger border-0 show" id="toastRuim">
            <div class="d-flex">
                <div class="toast-body">
                    <?=$message?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
            </div>
        </div>
    </div>
<?php }