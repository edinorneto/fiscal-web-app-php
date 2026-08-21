<?php
require_once 'config.php';
require_once 'data.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    header('Location: index.php');
    exit;
}

$id        = trim($_POST['id']        ?? '');
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

if ($id === '' || !ctype_digit($id)) {
    $erros[] = 'ID inválido.';
}

if (empty($nome)) {
    $erros[] = 'Nome é obrigatório.';
}

if (empty($categoria)) {
    $erros[] = 'Categoria é obrigatória.';
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
}

if ($ativo !== '0' && $ativo !== '1') {
    $erros[] = 'Status inválido.';
}

if (empty($erros)) {
    $novos_dados = [
        'nome'      => $nome,
        'descricao' => $descricao,
        'categoria' => $categoria,
        'ncm'       => $ncm,
        'preco'     => (float)$preco_normalizado,
        'estoque'   => (float)$estoque_normalizado,
        'unidade'   => $un,
        'ativo'     => (int)$ativo,
    ];

    $resultado = atualizar_produto(ARQUIVO_JSON, (int)$id, $novos_dados);

    if ($resultado !== false) {
        header('Location: produtos.php?status=updated');
        exit;
    }

    $erros[] = 'Produto não encontrado ou falha ao salvar. Verifique permissões do arquivo.';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
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
    <a href="editar.php?id=<?= urlencode((string)$id) ?>" class="btn-secondary">← Voltar e corrigir</a>

</body>
</html>
