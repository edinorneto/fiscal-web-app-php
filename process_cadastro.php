<?php

require_once 'config.php';
require_once 'data.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    header('Location: index.php');
    exit;
}

$nome      = trim($_POST['nome']      ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$ncm       = trim($_POST['ncm']       ?? '');
$preco     = trim($_POST['preco']     ?? '');
$estoque   = trim($_POST['estoque']   ?? '');
$preco_normalizado   = str_replace(',', '.', $preco);
$estoque_normalizado = str_replace(',', '.', $estoque);
$un        = trim($_POST['unidade']   ?? '');
$ativo     = trim((string)($_POST['ativo'] ?? '1'));

$erros = [];

$categorias_validas = [
    'Fertilizante',
    'Defensivo',
    'Semente',
    'Insumo'
];

$unidades_validas = [
    'kg',
    'T',
    'un',
    'L'
];

if (empty($nome)) {
    $erros[] = "Nome é obrigatório.";
}

if (empty($categoria)) {
    $erros[] = "Categoria é obrigatória.";
} elseif (!in_array($categoria, $categorias_validas, true)) {
    $erros[] = "Categoria inválida.";
}

if (strlen($ncm) !== 8 || !ctype_digit($ncm)) {
    $erros[] = 'NCM inválido: deve conter exatamente 8 dígitos.';
}

if (!is_numeric($preco_normalizado) || (float)$preco_normalizado <= 0) {
    $erros[] = 'Preço inválido.';
}

if (!is_numeric($estoque_normalizado) || (float)$estoque_normalizado < 0) {
    $erros[] = 'Estoque inválido.';
}

if (empty($un)) {
    $erros[] = 'Unidade é obrigatória.';
} elseif (!in_array($un, $unidades_validas, true)) {
    $erros[] = 'Unidade inválida.';
}

if ($ativo !== '0' && $ativo !== '1') {
    $erros[] = 'Status inválido.';
}

if (empty($erros)) {
    $produtos  = carregar_produtos(ARQUIVO_JSON);
    $ultimo_id = 0;

    foreach ($produtos as $p) {
        if (isset($p['id']) && (int)$p['id'] > $ultimo_id) {
            $ultimo_id = (int)$p['id'];
        }
    }

    $novo_produto = [
        'id'            => $ultimo_id + 1,
        'nome'          => $nome,
        'descricao'     => $descricao,
        'categoria'     => $categoria,
        'ncm'           => $ncm,
        'preco'         => (float)$preco_normalizado,
        'estoque'       => (float)$estoque_normalizado,
        'unidade'       => $un,
        'ativo'         => (int)$ativo,
        'data_cadastro' => date('d/m/Y H:i'),
    ];

    $produtos[] = $novo_produto;

    $salvou = salvar_produtos(ARQUIVO_JSON, $produtos);

    if ($salvou) {
        header('Location: produtos.php?status=created');
        exit;
    }

    $erros[] = 'Falha ao salvar o produto. Verifique permissões do arquivo.';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="alert alert-error">
        <span class="alert-icon">✗</span>
        <div>
            <?php foreach ($erros as $erro): ?>
                <p><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endforeach; ?>
        </div>
    </div>
    <a href="cadastro.php" class="btn-secondary">← Voltar e corrigir</a>
</body>
</html>
