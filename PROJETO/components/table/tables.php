<?php
include (ROOT . "/php/handlers/form_validator_php.php");
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
                $donation->tipo,
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

function render_users_table(array $table_columns, $users)
{
    ?><div hidden="hidden">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-record-circle-fill" viewBox="0 0 16 16" id="circle">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-8 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
    </svg>
</div>
    <table class="table table-hover table-amarela">
        <?php
        make_table_head($table_columns);
        while ($user = $users->fetch_object()) {
            $table_rows = [
                $user->nome,
                $user->email,
                formatPhoneNumber($user->telefone),
                formatCPF($user->cpf),
                $user->eh_doador == 1 ? '<div class="text-center pt-2">
<svg class="bi me-2" width="20" height="20" aria-hidden="true">
                <use xlink:href="#circle"/>
            </svg>
</div>' : '',
                $user->eh_voluntario == 1 ? '<div class="text-center pt-2">
<svg class="bi me-2" width="20" height="20" aria-hidden="true">
                <use xlink:href="#circle"/>
            </svg>
</div>' : '',
                $user->eh_adm == 1 ? '<div class="text-center pt-2">
<svg class="bi me-2" width="20" height="20" aria-hidden="true">
                <use xlink:href="#circle"/>
            </svg>
</div>' : '',
                //Suspender/Reativar
                '<form method="POST" action="index.php?adm=16">
                    <input type="hidden" name="email" value="' . $user->email . '">
                    <input type="hidden" name="' . ($user->suspenso ? 'reativar_suspenso' : 'suspender') . '" value="1">
                    <button type="submit" class="btn btn-' . ($user->suspenso ? 'success' : 'danger') . ' btn-sm">'
                . ($user->suspenso ? 'Reativar' : 'Suspender') .
                '</button>
                </form>',

                //Inativar/Reativar
                '<form method="POST" action="index.php?adm=16">
                    <input type="hidden" name="email" value="' . $user->email . '">
                    <input type="hidden" name="' . ($user->inativo ? 'reativar_inativo' : 'inativar') . '" value="1">
                    <button type="submit" class="btn btn-' . ($user->inativo ? 'success' : 'primary') . ' btn-sm">'
                . ($user->inativo ? 'Reativar' : 'Inativar') .
                '</button>
                </form>'
            ];

            echo "<tr>";
            foreach ($table_rows as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        ?>
    </table>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll("form button").forEach(function (button) {
                button.addEventListener("click", function () {
                    button.classList.remove("btn-success", "btn-danger", "btn-primary");
                    button.classList.add("btn-warning");
                });
            });
        });
    </script>
    <?php
}

