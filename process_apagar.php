<?php
require_once 'config.php';
require_once 'data.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    header('Location: index.php');
    exit;
}

$id    = trim($_POST['id'] ?? '');
$erros = [];

if ($id === '' || !ctype_digit($id)) {
    $erros[] = 'ID inválido.';
}

if (empty($erros)) {
    $apagado = apagar_produto(ARQUIVO_JSON, (int)$id);

    if ($apagado !== false) {
        header('Location: produtos.php?status=deleted');
        exit;
    }

    $erros[] = 'Produto não encontrado ou falha ao apagar. Verifique permissões do arquivo.';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apagar Produto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php if (!empty($erros)): ?>
    <div class="alert alert-error">
        <span class="alert-icon">✗</span>
        <div>
            <?php foreach ($erros as $erro): ?>
                <p><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endforeach; ?>
        </div>
    </div>
    <a href="produtos.php" class="btn-secondary">← Voltar</a>
<?php endif; ?>

</body>
</html>
