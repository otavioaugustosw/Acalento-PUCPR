<?php
/**
 * Formata uma data para o padrão brasileiro (dd/mm/yyyy).
 *
 * @param string $date Data no formato compatível com americano.
 * @return string Data formatada.
 */
function format_date(string $date): string
{
    return date('d/m/Y', strtotime($date));
}

/**
 * Formata uma hora no padrão de 24h (HH:mm).
 *
 * @param string $hour Hora no formato compatível com americano.
 * @return string Hora formatada.
 */
function format_hour(string $hour): string
{
    return date('H:i', strtotime($hour));
}

/**
 * Verifica se o evento já ocorreu com base na data e hora combinadas.
 *
 * @param object $event Objeto com as propriedades 'data' e 'hora'.
 * @return bool Verdadeiro se o evento já passou.
 */
function has_event_already_occurred(object $event): bool
{
    $event_datetime = new DateTime("{$event->data} {$event->hora}");
    $now = new DateTime();

    return $event_datetime < $now;
}

/**
 * Verifica se o evento ocorrerá dentro de uma quantidade específica de dias.
 *
 * @param object $event Objeto com a propriedade 'data' (string).
 * @param int $days Número de dias para comparação.
 * @return bool Verdadeiro se o evento está dentro do intervalo de dias.
 */
function is_event_in_days(object $event, int $days): bool
{
    $event_date = new DateTime($event->data);
    $today = new DateTime();

    $event_date->setTime(0, 0);
    $today->setTime(0, 0);

    $interval = $today->diff($event_date);

    return !$interval->invert && $interval->days < $days;
}
