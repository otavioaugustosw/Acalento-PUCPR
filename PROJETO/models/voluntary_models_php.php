<?php

function get_events_where($conn, $where)
{
    $query = "
        SELECT evento.*,
        assentamento.nome AS assentamento_nome,
        endereco.rua,
        endereco.numero,
        endereco.bairro,
        (SELECT COUNT(*) FROM usuario_participa_evento WHERE id_evento = evento.id) AS inscritos
        FROM evento
        LEFT JOIN assentamento ON evento.id_assentamento = assentamento.id
        LEFT JOIN endereco ON assentamento.id_endereco = endereco.id
        $where;
";
    return $conn->query($query);
}

function get_subscribed_events($conn, $user_id)
{
    $query_result = $conn->query("SELECT id_evento FROM usuario_participa_evento WHERE id_usuario = $user_id");
    $subscribed_events = [];
    while ($row = $query_result->fetch_object()) {
        $subscribed_events[] = $row->id_evento;
    }
    return $subscribed_events;
}