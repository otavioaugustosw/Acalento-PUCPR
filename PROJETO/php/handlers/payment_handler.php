<?php
function formataCampo($id, $valor) {
    return $id . str_pad(strlen($valor), 2, '0', STR_PAD_LEFT) . $valor;
}

function calculaCRC16($dados) {
    $resultado = 0xFFFF;
    for ($i = 0; $i < strlen($dados); $i++) {
        $resultado ^= (ord($dados[$i]) << 8);
        for ($j = 0; $j < 8; $j++) {
            if ($resultado & 0x8000) {
                $resultado = ($resultado << 1) ^ 0x1021;
            } else {
                $resultado <<= 1;
            }
            $resultado &= 0xFFFF;
        }
    }
    return strtoupper(str_pad(dechex($resultado), 4, '0', STR_PAD_LEFT));
}

function geraPixCode($chave, $valor, $nome = 'Acalento', $cidade = 'CURITIBA', $txid = '***') {
    $pix = "000201";
    $pix .= formataCampo("26", "0014br.gov.bcb.pix" . formataCampo("01", $chave));
    $pix .= "52040000";
    $pix .= "5303986";
    if ($valor > 0) {
        $pix .= formataCampo("54", number_format($valor, 2, '.', ''));
    }
    $pix .= "5802BR";
    $pix .= formataCampo("59", $nome);
    $pix .= formataCampo("60", $cidade);
    $pix .= formataCampo("62", formataCampo("05", $txid));
    $pix .= "6304";
    $pix .= calculaCRC16($pix);
    return $pix;
}

function validarComprovanteOCR($caminhoImagem)
{
    $imagemTratada = sys_get_temp_dir() . '/' . uniqid('tratada_') . '.png';

    // Pré-processamento com ImageMagick: aumenta contraste e converte para escala de cinza
    $comandoImagem = "convert " . escapeshellarg($caminhoImagem) . " -colorspace Gray -normalize -resize 150% " . escapeshellarg($imagemTratada);
    exec($comandoImagem);

    $tempSaida = tempnam(sys_get_temp_dir(), 'ocr');
    $comando = "tesseract " . escapeshellarg($imagemTratada) . " " . escapeshellarg($tempSaida) . " -l por 2>&1";
    exec($comando);

    $textoExtraido = @file_get_contents($tempSaida . '.txt');

    // Limpa arquivos temporários
    @unlink($tempSaida . '.txt');
    @unlink($imagemTratada);

    $valido = stripos($textoExtraido, 'pix') !== false && stripos($textoExtraido, 'r$') !== false;

    return [
        'valido' => $valido,
        'texto' => $textoExtraido
    ];
}
