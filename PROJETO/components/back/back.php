<?php
function make_buttom_back(string $caminho)
{ ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        #voltar:hover {
            color: var(--primary-background) !important;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
    <div class="mb-0 mt-5">
        <button id="voltar" class="d-flex align-items-center p-0 border-0 bg-transparent" style="color: #002B36; font-size: 1.1rem; font-family: var(--text-font)">
            <i class="bi bi-chevron-left me-2"></i>voltar
        </button>
    </div>

    <script>
        document.getElementById('voltar').addEventListener('click', function() {
            window.location.href = "<?= $caminho ?>";
        });
    </script>


<?php
} function make_buttom_onclick() { ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        #voltar:hover {
            color: var(--primary-background) !important;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>

    <div class="mb-0 mt-5">
        <button onclick="history.back()" class="d-flex align-items-center p-0 border-0 bg-transparent" style="color: #002B36; font-size: 1.1rem; font-family: var(--text-font)">
            <i class="bi bi-chevron-left me-2"></i>voltar
        </button>
    </div>

<?php }

