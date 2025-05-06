<?php
function make_table_rows($table_rows)
{
?>

<thead>
<tr>
    <?php
    foreach ($table_rows as $rows){?>
        <td><?= $rows ?></td>
    <?php } ?>
</tr>
</thead>

<?php
}

function make_table_head($table_columns)
{
?>

<thead>
<tr>
<?php
    foreach ($table_columns as $columns){?>
        <th scope="col"> <?= $columns ?> </th>
    <?php } ?>
</tr>
</thead>

<?php
}

function render_donator_donations_table(array $table_columns, $donations)
{
    ?>
    <table class="table table-hover table-amarela">
        <?php
        make_table_head($table_columns);
        while ($donation = $donations->fetch_object()) {
            $table_rows = [
                $donation->opcao_nome,
                $donation->quantidade,
                $donation->categoria,
                $donation->usuario_nome ?? 'Doador não cadastrado',
                format_date($donation->data),
                $donation->campanha_doacao_nome ?? 'Estoque'
            ];
            make_table_rows($table_rows);
        }
        ?>
    </table>
    <?php
}