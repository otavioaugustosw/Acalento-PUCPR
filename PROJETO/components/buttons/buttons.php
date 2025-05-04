<?php
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