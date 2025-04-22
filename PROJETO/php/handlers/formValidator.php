<?php

function displayValidation($fieldId, $isValid): void
{ ?>
    <script>
        var field = document.getElementById("<?= $fieldId ?>");
        field.classList.add("<?= $isValid ? "is-valid" : "is-invalid" ?>");
    </script>
<?php }

function hasContent($content): bool
{
    return (isset($content) && trim($content) !== '');
}

function isValidEmail($email): bool
{
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false;
}

function isFullName($name): bool
{
    if (!hasMaxLength($name, 50)) {
        return false;
    }
    return preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ]+(?: [A-Za-zÀ-ÖØ-öø-ÿ]+)+$/u', trim($name));
}

function isNumericOnly($value): bool
{
    return ctype_digit($value);
}

function isAlphaOnly(string $word): bool {
    $word = preg_replace('/\s+/', ' ', trim($word));
    return preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ ]+$/u', $word);
}

function hasMinLength($value, $min): bool
{
    return strlen(trim($value)) >= $min;
}

function hasMaxLength($value, $max): bool
{
    return strlen(trim($value)) <= $max;
}

function isCPFValid(string $cpf): bool {
    $cpf = preg_replace('/\D/', '', $cpf);

    if (strlen($cpf) !== 11 || !isNumericOnly($cpf)) {
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
    $firstDigit = ($remainder < 2) ? 0 : 11 - $remainder;
    if ($cpf[9] != $firstDigit) {
        return false;
    }

    $sum = 0;
    for ($i = 0, $weight = 11; $i < 10; $i++, $weight--) {
        $sum += $cpf[$i] * $weight;
    }
    $remainder = $sum % 11;
    $secondDigit = ($remainder < 2) ? 0 : 11 - $remainder;
    if ($cpf[10] != $secondDigit) {
        return false;
    }

    return true;
}

function isDateValid($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function isTimeValid($time) {
    $t = DateTime::createFromFormat('H:i:s', $time);
    return $t && $t->format('H:i:s') === $time;
}
