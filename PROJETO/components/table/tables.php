<?php
include_once (ROOT . "/php/handlers/time_handler.php");
include_once (ROOT . "/components/buttons/buttons.php");
include_once (ROOT . "/php/handlers/form_validator_php.php");
include_once (ROOT . "/models/admin_models_php.php");

function make_table_rows($table_rows, $extra = null)
{
    ?>
    <tbody>
    <tr>
        <?php
        foreach ($table_rows as $row){?>
            <td class="align-middle"><?= gettype($row) == "object" ? $row() : $row ?></td>
        <?php
        }
        if ($extra != null) {
            foreach ($extra as $table_rows){
                echo gettype($table_rows) == "object" ? $table_rows() : null;
            }
        }
        ?>
    </tr>
    </tbody>
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
            $button_inactivate = function () use ($user) {
                if ($user->inativo) {
                    makeButton("Reativar", "btn btn-dark-success", "index.php?adm=22&inativar=0&id_user=$user->id");
                }
                else {
                    makeButton("Inativar", "btn btn-danger", "index.php?adm=22&inativar=1&id_user=$user->id");
                }
            };

            $button_suspend = function () use ($user) {
                if ($user->suspenso) {
                    makeButton("Retirar", "btn btn-dark-success", "index.php?adm=22&suspender=0&id_user=$user->id");
                }
                else {
                    makeButton("Suspender", "btn btn-danger", "index.php?adm=22&suspender=1&id_user=$user->id");
                }
            };
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
                $button_suspend,
                $button_inactivate,
            ];
            make_table_rows($table_rows);
        }
        ?>
    </table>
    <?php
}

function render_certificates_table(array $table_columns, mysqli_result $events)
{ ?>
    <table class="table table-hover table-amarela">
        <?php make_table_head($table_columns); ?>
        <?php while ($event = $events->fetch_object()) {
            $button_certificate = function () use ($event) {
            if ($event->presenca) {
                makeButton("Visualizar certificado","btn btn-primary", "index.php?voluntary=9&nome_evento=$event->nome_evento&date=$event->data");
            }
            };
            $table_rows = [
                $event->nome_evento,
                date("d/m/Y", strtotime($event->data)),
                $button_certificate
            ];
            make_table_rows($table_rows);
        } ?>
    </table>
<?php }

function render_settlements_table(array $table_columns, mysqli_result $settlements_result): void
{ ?>
    <table class="table table-hover table-amarela">
        <?php make_table_head($table_columns); ?>
        <?php while ($settlement = $settlements_result->fetch_object()) {
            $button_edit = function () use ($settlement) {
                makeButton("Editar", "btn btn-primary", "index.php?adm=14&id=$settlement->id");
            };
            $button_delete = function () use ($settlement) {
                makeFormButton("index.php?adm=18", "inativar", "$settlement->id_estoque,$settlement->id", "Deletar", "btn btn-danger");


            };
            $table_rows = [
                $settlement->nome,
                $settlement->familias,
                $settlement->rua,
                $settlement->numero,
                $button_edit,
                $button_delete
            ];
            make_table_rows($table_rows);
        } ?>
    </table>
<?php }

function render_distribution_table(array $table_columns, array $fetched_data)
{
    foreach ($fetched_data as $data) {
        if ($data->id_estoque == 1) {
            if ($data->total_item > 0) {
                ?><h2 id="quantidadeDisponivel"><?=$data->total_item . ($data->unidade == 'u' ? " unidades" : $data->unidade) . " de " . $data->nome_item . " disponíveis para distribuir"?></h2>
                <?php
            }
            else {
                ?><h2><?="Não há " . $data->nome_item . " disponíveis para distribuir"?></h2>
                <?php
            }
            break;
        }
    }
    ?>

    <table class="table table-hover table-amarela">
        <?php
        make_table_head($table_columns);
        $available_quantity = 0;
        foreach ($fetched_data as $data) {
            if ($data->id_estoque == 1) {
                $available_quantity = $data->total_item;
                continue;
            }
            $ratio = get_settlement_ratio($data->quantidade_familias, get_total_families($fetched_data));
            $distributed = empty($_POST["quantidade$data->id_estoque"] ?? get_item_quantity_by_ratio($ratio, $available_quantity)) ? 0 : ($_POST["quantidade$data->id_estoque"] ?? get_item_quantity_by_ratio($ratio, $available_quantity));

            if ($distributed < $data->quantidade_familias) {
                show_warning(custom: "As doações podem ser insuficientes para todas famílias de $data->nome_assentamento");
            }
            $actual_quantity_row = function () use ($data) {?>
                <td id="actual<?= $data->id_estoque ?>" class="align-middle"><?= $data->total_item ?? 0 ?></td>
            <?php };

            $distribution_field = function () use ($distributed, $data) {?>
                <td class="align-middle">
                    <div style="width: 5em">
                        <input type="number" min="0" max="999" class="form-control text-center" id="inputQuantidade<?= $data->id_estoque ?>" name="quantidade<?= $data->id_estoque ?>" value="<?= $distributed ?>">
                    </div>
                </td>
            <?php };

            $total_row = function () use ($data, $distributed) {?>
                <td id="total<?= $data->id_estoque ?>" class="align-middle"><?= $distributed + $data->total_item ?? 0 ?></td>
            <?php };



            make_table_rows([
                $data->nome_assentamento,
                $data->nome_estoque,
                $data->quantidade_familias,
                $ratio . "%",
            ],
                [
                    $actual_quantity_row,
                    $distribution_field,
                    $total_row
                ]
            );
        }
        ?>
    </table>
    <div class="w-25 d-flex">
        <?php makeButton("Distribuir", "btn btn-primary w-100", "", submit: true );?>
    </div>
    <?php
}

function render_decrement_table(array $table_columns, array $fetched_data)
{?>
    <table class="table table-hover table-amarela">
        <?php

        make_table_head($table_columns);
        foreach ($fetched_data as $data) {
            if ($data->id_estoque == 1) {
                continue;
            }
            $distribution_field = function () use ($data) {?>
                <td class="align-middle">
                    <div style="width: 5em">
                        <input type="hidden" id="actual<?= $data->id_estoque ?>" name="actual<?= $data->id_estoque ?>" value="<?=$data->total_item?>">
                        <input type="number" min="0" max="999" class="form-control text-center" id="inputQuantidade<?= $data->id_estoque ?>" name="quantidade<?= $data->id_estoque ?>" value="0">
                    </div>
                </td>
            <?php };

            $total_row = function () use ($data) {?>
                <td id="total<?= $data->id_estoque ?>" class="align-middle"><?=$data->total_item ?? 0 ?></td>
            <?php };

            make_table_rows([
                $data->nome_assentamento,
                $data->nome_estoque,
            ],
                [
                    $distribution_field,
                    $total_row
                ]
            );
        }
        ?>
    </table>
    <div class="w-25 d-flex">
        <?php makeButton("Registrar saída", "btn btn-primary w-100", submit: true );?>
    </div>
    <?php
}