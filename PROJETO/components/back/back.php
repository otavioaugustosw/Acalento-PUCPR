<?php
function make_buttom_back()
{ ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <div class="mb-0 mt-5">
        <button class="d-flex align-items-center p-0 border-0 bg-transparent" onclick="history.back()" style="color: #002B36; font-size: 1.1rem; font-family: var(--text-font)">
            <i class="bi bi-chevron-left me-2"></i>voltar
        </button>
    </div>
<?php
}
