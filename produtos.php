<?php
require_once 'data.php';
require_once 'config.php';

$produtos = carregar_produtos(ARQUIVO_JSON);

$status = $_GET['status'] ?? '';
$flashHtml = '';
if ($status === 'created') {
    $flashHtml = '<div class="alert alert-success"><span class="alert-icon">✓</span> Produto cadastrado com sucesso!</div>';
} elseif ($status === 'updated') {
    $flashHtml = '<div class="alert alert-success"><span class="alert-icon">✓</span> Produto atualizado com sucesso!</div>';
} elseif ($status === 'deleted') {
    $flashHtml = '<div class="alert alert-success"><span class="alert-icon">✓</span> Produto apagado com sucesso!</div>';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <?php if ($flashHtml !== ''): ?>
            <?php echo $flashHtml; ?>
        <?php endif; ?>
        <section class="card">
            <header class="card-header">
                <h1>Lista de Produtos</h1>
                <a href="cadastro.php" class="btn btn-primary">Novo Produto</a>
            </header>

            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>NCM</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($produtos)) { ?>
                            <?php foreach ($produtos as $item) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($item['categoria'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($item['ncm'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>R$ <?= htmlspecialchars(number_format((float)($item['preco'] ?? 0), 2, ',', '.'), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars(number_format((float)($item['estoque'] ?? 0), 2, ",", "."), ENT_QUOTES, 'UTF-8') ?></td>
                                    <?php $ativo = !empty($item['ativo']); ?>
                                    <td><?= $ativo ? 'Ativo' : 'Inativo' ?></td>
                                    <td>
                                        <a href="editar.php?id=<?= urlencode((string)($item['id'] ?? '')) ?>">Editar</a>
                                        <form action="process_status.php" method="post" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars((string)($item['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                            <button type="submit" onclick="return confirm('Tem certeza que deseja alterar o status deste produto?');"><?= $ativo ? 'Inativar' : 'Ativar' ?></button>
                                        </form>
                                        <form action="process_apagar.php" method="post" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars((string)($item['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                            <button type="submit" onclick="return confirm('Tem certeza que deseja apagar este produto?');">Apagar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="7">Nenhum produto encontrado.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
