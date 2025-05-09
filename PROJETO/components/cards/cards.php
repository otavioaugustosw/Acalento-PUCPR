<?php
include_once (ROOT. '/components/back/back.php');
include_once (ROOT . '/components/buttons/buttons.php');
include_once (ROOT . '/components/modal/modal.php');
function make_text_card($title, $text1, $text2, $buttons = null)
{?>
    <div class="col">
        <div class="card h-100 amarelo">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= $title ?></h5>
                <p class="card-text"><?= $text1 ?></p>
                <p class="card-text"><?= $text2 ?></p>
                <div class="mt-auto d-flex justify-content-between">
                    <?= isset($buttons) ? $buttons() : null ?>
                </div>
            </div>
        </div>
    </div>
<?php }
function make_vertical_card($title, $text1,$text2, $text3, $sub_text, $image_Link = "assets/imagens/default.jpg", $buttons = null, $extra = null)
{ ?>
    <div class="col">
        <div class="card vertical h-100 amarelo horizontal ">
            <figure class="imagem-vertical degrade-vertical">
                <img src="<?= $image_Link != "" ? $image_Link : "assets/imagens/default.jpg" ?>" class="card-img-top">
            </figure>
            <div class="card-body">
                <h5 class="card-title"><?= $title ?></h5>
                <p class="card-text"><?= $text1 ?></p>
                <p class="card-text"><?= $text2 ?></p>
                <p class="card-text"><?= $text3 ?></p>
                <p class="card-text descricao"><?= $sub_text ?></p>
                <div class="mt-auto">
                <div class="row">
                <?= isset($buttons) ? $buttons() : null ?>
                </div>
                </div>
                <?= isset($extra) ? $extra() : null ?>
            </div>
        </div>
    </div>
<?php
}
function make_horizontal_card($title, $text1,$text2, $text3, $sub_text, $image_Link = "assets/imagens/default.jpg", $buttons = null, $extra = null)
{ ?>
    <div class="col">
        <div class="card mb-3 amarelo mx-auto" >
            <div class="row g-0">
                <div class="col-md-4 degrade-horizontal">
                    <figure class="imagem-horizontal">
                        <img src="<?= $image_Link != "" ? $image_Link : "assets/imagens/default.jpg"  ?>" class="img-fluid rounded-start" alt="...">
                    </figure>
                </div>
                <div class="col-md-8">
                    <div class="card-body d-flex flex-column ">
                        <h5 class="card-title"><?= $title ?></h5>
                        <p class="card-text"><?= $text1 ?></p>
                        <p class="card-text"><?= $text2 ?></p>
                        <p class="card-text"><?= $text3 ?></p>
                        <p class="card-text descricao"><?= $sub_text ?></p>
                        <div class="mt-auto d-flex justify-content-between gap-2">
                            <?= isset($buttons) ? $buttons() : null ?>
                        </div>
                        <?= isset($extra) ? $extra() : null ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }

function render_campaigns_card($campaigns)
{
    while ($event = $campaigns->fetch_object()) {
        $buttons = function () use ($event) {
            makeButton("Visualizar doações", "btn btn-primary", "index.php?adm=10&id=$event->id");
        };
        make_text_card($event->nome, format_date($event->data), $event->assentamento_nome, $buttons);
    }
}

function render_events_card(
        $events,
        $subscribed_events = null,
        bool $admin = false,
        bool $voluntary = false,
        bool $horizontal = false,
)
{
    if ($admin) {
        while ($event = $events->fetch_object()) {
            $horizontal ? horizontal_admin_event_card($event) : vertical_admin_event_card($event);
        }
        return;
    }
    if ($voluntary) {
        while ($event = $events->fetch_object()) {
            $horizontal ? horizontal_voluntary_event_card($event, $subscribed_events) : vertical_voluntary_event_card($event, $subscribed_events);
        }
        return;
    }
}

function vertical_admin_event_card($event)
{
    $data_formatada = format_date($event->data);
    $hora_formatada = format_hour($event->hora);

    $buttons_render = function () use ($event) {
        makeButton("Editar", "btn btn-primary", "index.php?adm=6&id=$event->id");
        makeModal(
            $event->id,
            button_text: 'Deletar',
            modal_title: 'Confirmar exclusão',
            modal_body: 'Tem certeza que deseja deletar esse evento',
            cancel_text: "Cancelar",
            confirm_text: 'Sim, deletar',
            form_action: "index.php?adm=4&id=$event->id"
        );
    };
    make_vertical_card(
        $event->nome,
        $event->assentamento_nome,
        "$data_formatada às $hora_formatada",
        "$event->inscritos/$event->lotacao_max inscritos",
        $event->descricao,
        $event->link_imagem,
        $buttons_render
    );
}

function horizontal_admin_event_card($event)
{
    $data_formatada = format_date($event->data);
    $hora_formatada = format_hour($event->hora);

    $buttons_render = function () use ($event) {
        makeButton("Editar", "btn btn-primary", "index.php?adm=6&id=$event->id");
        makeModal(
            $event->id,
            button_text: 'Deletar',
            modal_title: 'Confirmar exclusão',
            modal_body: 'Tem certeza que deseja deletar esse evento',
            cancel_text: "Cancelar",
            confirm_text: 'Sim, deletar',
            form_action: "index.php?adm=4&id=$event->id"
        );
    };
    make_horizontal_card(
            $event->nome,
            $event->assentamento_nome,
            "$data_formatada às $hora_formatada",
            "$event->inscritos/$event->lotacao_max inscritos",
            $event->descricao,
            $event->link_imagem,
            $buttons_render
    );
}

function vertical_voluntary_event_card($event, $subscribed_events)
{
    $data_formatada = format_date($event->data);
    $hora_formatada = format_hour($event->hora);

    $buttons_render = function () use ($event, $subscribed_events) {
        if (in_array($event->id, $subscribed_events)) {
            makeModal($event->id, button_text: 'Cancelar inscrição',
                modal_title: 'Cancelar inscrição',
                modal_body: 'Tem certeza que deseja cancelar a inscrição?',
                confirm_text: 'Sim, cancelar',
                form_action: 'index.php?voluntary=1');
        } else if ($event->inscritos >= $event->lotacao_max) {
            makeButton("Evento Lotado", "btn btn-secondary");
        } else {
            makeFormButton('index.php?voluntary=3', 'id_evento', $event->id, 'Inscrever-se');
        }
    };
    make_vertical_card(
        $event->nome,
        "$data_formatada às $hora_formatada",
        $event->assentamento_nome,
        "$event->inscritos/$event->lotacao_max inscritos",
        $event->descricao,
        $event->link_imagem,
        $buttons_render
    );
}

function horizontal_voluntary_event_card($event, $subscribed_events)
{
    $data_formatada = format_date($event->data);
    $hora_formatada = format_hour($event->hora);

    $buttons_render = function () use ($event, $subscribed_events) {
        if (in_array($event->id, $subscribed_events)) {
            makeModal($event->id, button_text: 'Cancelar inscrição',
                modal_title: 'Cancelar inscrição',
                modal_body: 'Tem certeza que deseja cancelar a inscrição?',
                confirm_text: 'Sim, cancelar',
                form_action: 'index.php?voluntary=1');
        } else if ($event->inscritos >= $event->lotacao_max) {
            makeButton("Evento Lotado", "btn btn-secondary");
        } else {
            makeFormButton('index.php?voluntary=3', 'id_evento', $event->id, 'Inscrever-se');
        }
    };
    make_horizontal_card(
        $event->nome,
        "$data_formatada às $hora_formatada",
        $event->assentamento_nome,
        "$event->inscritos/$event->lotacao_max inscritos",
        $event->descricao,
        $event->link_imagem,
        $buttons_render
    );
}