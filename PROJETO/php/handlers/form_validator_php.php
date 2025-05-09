<?php
/**
 * Exibe a validação de um campo com base no seu ID.
 *
 * @param string $field_id ID do campo a ser validado.
 * @param bool $is_valid Determina se o campo é válido ou inválido.
 *
 * @return void
 */
function display_validation(string $field_id, bool $is_valid): void
{
    ?>
    <script>
        var field = document.getElementById("<?= $field_id ?>");
        field.classList.add("<?= $is_valid ? "is-valid" : "is-invalid" ?>");
    </script>
    <?php
}

/**
 * Verifica se o conteúdo não está vazio.
 *
 * @param string $content Conteúdo a ser verificado.
 *
 * @return bool Retorna true se o conteúdo não for vazio, false caso contrário.
 */
function has_content($content): bool
{
    return (isset($content) && trim($content) !== '');
}

/**
 * Verifica se o e-mail fornecido é válido.
 *
 * @param string $email E-mail a ser verificado.
 *
 * @return bool Retorna true se o e-mail for válido, false caso contrário.
 */
function is_valid_email(string $email): bool
{
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Verifica se o nome completo é válido.
 *
 * @param string $name Nome completo a ser verificado.
 *
 * @return bool Retorna true se o nome completo for válido, false caso contrário.
 */
function is_full_name(string $name): bool
{
    if (!has_max_length($name, 50)) {
        return false;
    }
    return preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ]+(?: [A-Za-zÀ-ÖØ-öø-ÿ]+)+$/u', trim($name));
}

/**
 * Verifica se o valor contém apenas números.
 *
 * @param mixed $value Valor a ser verificado.
 *
 * @return bool Retorna true se o valor for numérico, false caso contrário.
 */
function is_numeric_only($value): bool
{
    return ctype_digit((string)$value);
}

/**
 * Verifica se a palavra contém apenas letras.
 *
 * @param string $word Palavra a ser verificada.
 *
 * @return bool Retorna true se a palavra contiver apenas letras, false caso contrário.
 */
function is_alpha_only(string $word): bool
{
    $word = preg_replace('/\s+/', ' ', trim($word));
    return preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ ]+$/u', $word);
}

/**
 * Verifica se o valor possui comprimento mínimo.
 *
 * @param string $value Valor a ser verificado.
 * @param int $min Comprimento mínimo.
 *
 * @return bool Retorna true se o valor tiver o comprimento mínimo, false caso contrário.
 */
function has_min_length(string $value, int $min): bool
{
    return strlen(trim($value)) >= $min;
}

/**
 * Verifica se o valor possui comprimento máximo.
 *
 * @param string $value Valor a ser verificado.
 * @param int $max Comprimento máximo.
 *
 * @return bool Retorna true se o valor tiver o comprimento máximo, false caso contrário.
 */
function has_max_length(string $value, int $max): bool
{
    return strlen(trim($value)) <= $max;
}

/**
 * Verifica se o CPF fornecido é válido.
 *
 * @param string $cpf CPF a ser verificado.
 *
 * @return bool Retorna true se o CPF for válido, false caso contrário.
 */
function is_cpf_valid(string $cpf): bool
{
    $cpf = preg_replace('/\D/', '', $cpf);

    if (strlen($cpf) !== 11 || !is_numeric_only($cpf)) {
        return false;
    }

    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    $sum = 0;
    for ($i = 0, $weight = 10; $i < 9; $i++, $weight--) {
        $sum += $cpf[$i] * $weight;
    }
    $remainder = $sum % 11;
    $first_digit = ($remainder < 2) ? 0 : 11 - $remainder;
    if ($cpf[9] != $first_digit) {
        return false;
    }

    $sum = 0;
    for ($i = 0, $weight = 11; $i < 10; $i++, $weight--) {
        $sum += $cpf[$i] * $weight;
    }
    $remainder = $sum % 11;
    $second_digit = ($remainder < 2) ? 0 : 11 - $remainder;
    if ($cpf[10] != $second_digit) {
        return false;
    }

    return true;
}

/**
 * Verifica se a data fornecida é válida.
 *
 * @param string $date Data no formato 'Y-m-d'.
 *
 * @return bool Retorna true se a data for válida, false caso contrário.
 */
function is_date_valid(string $date): bool
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}