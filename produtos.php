<?php
require_once 'data.php';
require_once 'config.php';

$produtos = carregar_produtos(ARQUIVO_JSON);
$status   = $_GET['status'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos — Cadastro Fiscal PHP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="page-header-wide">
        <a href="index.php" class="nav-back">Menu principal</a>
        <div class="eyebrow">Gerenciamento</div>
        <h1>Lista de <span>Produtos</span></h1>
    </div>

    <div class="card-wide">

        <div class="card-header">
            <div class="card-header-left">
                <div class="dot"></div>
                <h2>Produtos cadastrados</h2>
            </div>
            <div style="display:flex; align-items:center; gap:14px;">
                <span class="table-count">
                    <strong><?= count($produtos) ?></strong>
                    produto<?= count($produtos) !== 1 ? 's' : '' ?>
                </span>
                <a href="cadastro.php" class="btn-action btn-action-activate">+ Novo produto</a>
            </div>
        </div>

        <?php if ($status !== ''): ?>
            <div style="padding: 16px 24px 0;">
                <?php if ($status === 'created'): ?>
                    <div class="alert alert-success" style="max-width:none;"><span class="alert-icon">✓</span> Produto cadastrado com sucesso!</div>
                <?php elseif ($status === 'updated'): ?>
                    <div class="alert alert-success" style="max-width:none;"><span class="alert-icon">✓</span> Produto atualizado com sucesso!</div>
                <?php elseif ($status === 'deleted'): ?>
                    <div class="alert alert-success" style="max-width:none;"><span class="alert-icon">✓</span> Produto apagado com sucesso!</div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($produtos)): ?>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th class="col-categoria">Categoria</th>
                        <th>NCM</th>
                        <th>Preço</th>
                        <th class="col-estoque">Estoque</th>
                        <th>Status</th>
                        <th style="text-align:right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $item): ?>
                        <?php $ativo = !empty($item['ativo']); ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="td-muted col-categoria"><?= htmlspecialchars($item['categoria'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="td-muted"><?= htmlspecialchars($item['ncm'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td>R$ <?= htmlspecialchars(number_format((float)($item['preco'] ?? 0), 2, ',', '.'), ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="td-muted col-estoque">
                                <?= htmlspecialchars(number_format((float)($item['estoque'] ?? 0), 2, ',', '.'), ENT_QUOTES, 'UTF-8') ?>
                                <?= htmlspecialchars($item['unidade'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td>
                                <?php if ($ativo): ?>
                                    <span class="badge b-g">Ativo</span>
                                <?php else: ?>
                                    <span class="badge b-r">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td class="td-actions">
                                <div class="action-group">
                                    <a href="editar.php?id=<?= urlencode((string)($item['id'] ?? '')) ?>"
                                       class="btn-action btn-action-edit">Editar</a>

                                    <form action="process_status.php" method="post" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars((string)($item['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                        <?php if ($ativo): ?>
                                            <button type="submit"
                                                    class="btn-action btn-action-deactivate"
                                                    onclick="return confirm('Inativar este produto?');">Inativar</button>
                                        <?php else: ?>
                                            <button type="submit"
                                                    class="btn-action btn-action-activate"
                                                    onclick="return confirm('Ativar este produto?');">Ativar</button>
                                        <?php endif; ?>
                                    </form>

                                    <form action="process_apagar.php" method="post" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars((string)($item['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                        <button type="submit"
                                                class="btn-action btn-action-delete"
                                                onclick="return confirm('Apagar permanentemente este produto?');">Apagar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <div class="empty-state-title">Nenhum produto cadastrado</div>
                <div class="empty-state-desc">Cadastre o primeiro produto para começar a usar o simulador fiscal.</div>
                <a href="cadastro.php" class="btn-primary" style="max-width:220px; margin:0 auto;">Cadastrar produto →</a>
            </div>
        <?php endif; ?>

    </div>

    <footer class="page-footer">
        Projeto de estudo · <a href="https://www.linkedin.com/in/edinor-de-souza-neto/" target="_blank" rel="noopener noreferrer">Edinor de Souza Neto</a> · PHP
    </footer>

</body>
</html>
