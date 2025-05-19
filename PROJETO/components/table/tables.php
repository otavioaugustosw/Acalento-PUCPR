<?php
include_once (ROOT . "/php/handlers/time_handler.php");
include_once (ROOT . "/components/buttons/buttons.php");
function make_table_rows($table_rows)
{
?>

<thead>
<tr>
    <?php
    foreach ($table_rows as $row){?>
        <td><?= gettype($row) == "object" ? $row() : $row ?></td>
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


function render_punishments_table(array $table_columns, mysqli_result $punishments, bool $common = false)
{?>
    <table class="table table-hover table-amarela">
        <?php
        make_table_head($table_columns);
        while ($punishment = $punishments->fetch_object()) {
            $status = function () use ($punishment) {
                $status_title = "Pendente";
                $status_classes = "btn btn-primary";
                if (!$punishment->revisado) {
                    $status_title = "Revisão pendente";
                    $status_classes = "btn btn-secondary";
                }
                elseif ($punishment->inativo) {
                    $status_title = "Retirado";
                    $status_classes = "btn btn-dark-success";
                }
                else {
                    $status_title = "Penalizado";
                    $status_classes = "btn btn-dark-danger";
                }

                makeButton($status_title, $status_classes);
            };

            $actionButton = function () use ($punishment, $common) {
                makeButton(
                        "Ver mais",
                    "btn btn-primary",
                    $common ? "index.php?common=17&id=" . $punishment->punicao_id : "index.php?adm=10&id=" . $punishment->punicao_id
                );
            };
            $table_rows = [
                "#" . $punishment->punicao_id,
                $punishment->usuario_nome,
                $punishment->usuario_email,
                format_date($punishment->data_punicao),
                $punishment->evento_nome ?? "N/A",
                $status,
                $actionButton
            ];
            make_table_rows($table_rows);
        }
        ?>
    </table>
<?php }

function render_checkin_table(array $table_columns, mysqli_result $volunteers, $event, $disable = false)
{?>
    <table class="table table-hover table-amarela">
        <?php
        make_table_head($table_columns);
        while ($voluntary = $volunteers->fetch_object()) {
            $toggle_checkin_button = function () use ($voluntary, $disable,  $event) {
                if ($voluntary->presenca ) {
                    makeButton("Presente", "btn btn-success", $disable ? '' : "index.php?adm=13&presenca=1&iduser=$voluntary->usuario_id&id=$event->id");
                }
                else {
                    makeButton("Ausente", "btn btn-danger", $disable ? '' : "index.php?adm=13&presenca=1&iduser=$voluntary->usuario_id&id=$event->id");
                }
            };
            $table_rows = [
                $voluntary->usuario_nome,
                $toggle_checkin_button
            ];
            make_table_rows($table_rows);
        }
        ?>
    </table>
<?php }
