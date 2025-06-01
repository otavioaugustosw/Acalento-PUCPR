
<?php
function makeModal(
     $id,
     $modal_id = null,
     $button_classes = 'btn btn-danger largura-completa',
     $button_text = 'TITULO BOTÃO',
     $modal_title = 'CONFIRMAR AÇÃO',
     $modal_body = 'QUER CONTINUAR?',
     $cancel_text = 'Não, voltar',
     $confirm_text = 'Sim, confirmar',
     $confirm_btn_class = 'btn btn-danger',
     $form_action = 'index.php',
     $form_method = 'POST'
) {
    ?>
    <div class="col">

    <button type="button" class="<?= $button_classes ?>" data-bs-toggle="modal" data-bs-target="#modal_action_<?= $id , $modal_id?>">
        <?= $button_text ?>
    </button>
    </div>

    <div class="modal fade" id="modal_action_<?= $id , $modal_id?>" tabindex="-1" aria-labelledby="modal_label_<?= $id , $modal_id?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content amarelo">
                <form action="<?= $form_action ?>" method="<?= $form_method ?>">
                    <input type="hidden" name="id_evento" value="<?= $id ?>">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_label_<?= $id , $modal_id?>"><?= $modal_title ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <?= $modal_body ?>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $cancel_text ?></button>
                        <button type="submit" class="<?= $confirm_btn_class ?>"><?= $confirm_text ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
}

function make_form_modal(
    $button_classes = 'btn btn-primary largura-completa',
    $button_text = 'TITULO BOTÃO',
    $modal_title = 'CONFIRMAR AÇÃO',
    $confirm_text = 'Salvar alterações',
    $confirm_btn_class = 'w-100 mb-2 btn btn-lg rounded-3 btn-primary',
    $form_action = 'index.php',
    $form_method = 'POST',
    $modal_inputs = null
)  {?>
    <div class="col">

        <button type="button" class="<?= $button_classes ?>" data-bs-toggle="modal" data-bs-target="#modal_action">
            <?= $button_text ?>
        </button>
    </div>

    <div class="modal fade" id="modal_action" tabindex="-1" aria-labelledby="modal_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content amarelo">

                <form action="<?= $form_action ?>" method="<?= $form_method ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_label"><?= $modal_title ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="">
                            <?= $modal_inputs ? $modal_inputs() : null?>
                    </div>
                    <div class="modal-footer">
                        <button class="<?= $confirm_btn_class ?>" type="submit"> <?= $confirm_text ?> </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php }
