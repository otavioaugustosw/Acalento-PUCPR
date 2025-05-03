<?php
function makeFilter()
{?>
    <form method="POST" action="" class="mb-4">
        <div class="row g-3 align-items-center">

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
                <label for="dia" class="form-label">Data</label>
                <input type="date" name="dia" id="dia" class="form-control">
            </div>

            <!-- Botão alinhado verticalmente com campos -->
            <div class="col-md-2 ">
                <button type="submit" class="btn btn-primary w-100">
                    Filtrar
                </button>
            </div>

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

