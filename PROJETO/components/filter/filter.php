<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/default.css">
    <title>Acalento | Editar evento</title>
</head>
<body>
<form method="POST" class="mb-4" action="">
    <div class="row g-2 align-items-end">
        <div class="col-md-3">
            <label for="filtro" class="form-label">Exibir:</label>
            <select name="filtro" id="filtro" class="form-select" onchange="mostrarFiltroData()">
                <option value="">Selecione</option>
                <option value="futuros">Futuros</option>
                <option value="passados">Passados</option>
                <option value="todos">Todos</option>
                <option value="mes">Último mês</option>
                <option value="data">Por dia</option>
            </select>
        </div>

        <!-- Campo de data exata -->
        <div class="col-auto" id="filtroData" style="display: none;">
            <label for="dia" class="form-label">Data</label>
            <input type="date" name="dia" id="dia" class="form-control">
        </div>

        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>

    </div>
</form>

<script>
    function mostrarFiltroData() {
        const tipo = document.getElementById('filtro').value;
        document.getElementById('filtroData').style.display = tipo === 'data' ? 'block' : 'none';
    }
</script>
</body>
</html>
