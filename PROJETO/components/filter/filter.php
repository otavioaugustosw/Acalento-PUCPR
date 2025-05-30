<?php
include_once (ROOT . "/components/buttons/buttons.php");
function makeFilter($donation = false)
{?>
    <form method="POST" action="" class="mb-4">
        <div class="row g-3 align-items-center">
        <?php if($donation) { ?>
            <div class="col-md-3">
                <select name="filtro" id="filtro" class="form-select" onchange="mostrarFiltroData()">
                    <option value="">Selecione</option>
                    <option value="passados">Passados</option>
                    <option value="todos">Todos</option>
                    <option value="mes">Último mês</option>
                    <option value="data">Por dia</option>
                </select>
            </div>

            <!-- Campo de data (visível condicionalmente) -->
            <div class="col-md-3" id="filtroData" style="display: none;">
                <input type="date" name="dia" id="dia" class="form-control">
            </div>

            <div class="col-md-2">
                <?php makeButton("Filtrar", "btn btn-primary w-100", "", true );?>
            </div>

        <?php } else { ?>
            <!-- Campo de filtro -->
            <div class="col-md-3">
                <select name="filtro" id="filtro" class="form-select" onchange="mostrarFiltroData()">
                    <option value="">Selecione</option>
                    <option value="futuros">Futuros</option>
                    <option value="passados">Passados</option>
                    <option value="todos">Todos</option>
                    <option value="mes">Último mês</option>
                    <option value="data">Por dia</option>
                </select>
            </div>

            <!-- Campo de data (visível condicionalmente) -->
            <div class="col-md-3" id="filtroData" style="display: none;">
                <input type="date" name="dia" id="dia" class="form-control">
            </div>

            <!-- Botão alinhado verticalmente com campos -->
            <div class="col-md-2 ">
                <?php makeButton("Filtrar", "btn btn-primary w-100", "", true );?>
            </div>
            <?php } ?>
        </div>
    </form>

    <script>
        function mostrarFiltroData() {
            const tipo = document.getElementById('filtro').value;
            document.getElementById('filtroData').style.display = tipo === 'data' ? 'block' : 'none';
        }
    </script>
<?php
}

function make_filter_events (){ ?>
    <form method="POST" action="" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <select name="filtro" id="filtro" class="form-select">
                    <option value="">Selecione</option>
                    <option value="ha_confirmar">Necessário a confirmação</option>
                    <option value="confirmado">Presença confirmada</option>
                    <option value="presente">Presentes</option>
                    <option value="todos">Todos</option>
                </select>
            </div>

            <div class="col-md-2 ">
            <?php makeButton("Filtrar", "btn btn-primary w-100", "", true );?>
            </div>
        </div>
    </form>
<?php }

function make_filter_user() { ?>
    <form method="POST" action="" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <select name="filtro" id="filtro" class="form-select">
                    <option value="">Selecione</option>
                    <option value="todos">Todos</option>
                    <option value="voluntario">Voluntário</option>
                    <option value="administrador">Administrador</option>
                    <option value="doador">Doador</option>
                </select>
            </div>

            <div class="col-md-2 ">
            <?php makeButton("Filtrar", "btn btn-primary w-100", "", true );?>
            </div>
        </div>
    </form>
<?php }

function make_filter_validate()
{ ?>
<form method="POST" action="" class="mb-4">
    <div class="row g-3 align-items-center">
        <div class="col-md-4">
            <select name="filtro" id="filtro" class="form-select">
                <option value="">Selecione</option>
                <option value="todos">Todos</option>
                <option value="aprovar">Aprovar</option>
                <option value="aprovados">Aprovados</option>
                <option value="nao_aprovados">Não aprovados</option>
            </select>
        </div>

        <div class="col-md-2 ">
            <?php makeButton("Filtrar", "btn btn-primary w-100", "", true );?>
        </div>
    </div>
</form>
<?php
}

