<?php


function make_vertical_card( $title, $text1,$text2, $text3, $sub_text, $image_Link, $buttons = null, $extra = null)
{ ?>
    <div class="col">
        <div class="card h-100 amarelo">
            <figure class="imagem-vertical degrade-vertical">
                <img src="<?= $image_Link ?>" class="card-img-top">
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

function makeButton(string $title, string $classes, string $perform = "", $submit = false)
{
    if ($submit) {
        ?>
        <button type="submit" class="<?= $classes ?> largura-completa"><?= $title ?></button>
        <?php
    } else {
        ?>
        <div class="col">
            <a href="<?= $perform ?>" class="<?= $classes ?> largura-completa"><?= $title ?></a>
        </div>
        <?php
    }
}

function makeFormButton($perform, $hidden_name, $hidden_value, $button_text, $button_class = 'btn btn-primary') {
    ?>
<div class="col">
<form action="<?= $perform ?>" method="POST">
        <input type="hidden" name="<?= $hidden_name ?>" value="<?= $hidden_value ?>">
        <?php makeButton($button_text, $button_class, submit: true) ?>
    </form>
</div>
    <?php
}
