<?php 
require_once 'data.php';
require_once 'config.php';

$id = trim((string)($_GET['id'] ?? ''));

if ($id === '' || !ctype_digit($id)) {
    header('Location: produtos.php');
    exit;
}

$produtos = carregar_produtos(ARQUIVO_JSON);
$produto = null;

foreach ($produtos as $item) {
    if ((int)$id === (int)($item['id'] ?? 0)) {
        $produto = $item;
        break;
    }
}

if (!$produto) {
    header('Location: produtos.php');
    exit;
}

$ativo = !empty($produto['ativo']);
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
    <div class="page-header">
        <a class="nav-back" href="produtos.php">Voltar para lista</a>
        <div class="eyebrow">Editar produto</div>
        <h1>Editar <span>Produto</span></h1>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-header-left">
                <div class="dot"></div>
                <h2>Dados do Produto</h2>
            </div>
        </div>

        <div class="card-body">
            <form action="process_editar.php" method="post">
                <input type="hidden" name="id" value="<?= htmlspecialchars((string)($produto['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">

                <div class="form-group">
                    <label for="nome">Nome do produto <span class="req">*</span></label>
                    <input type="text" id="nome" name="nome" placeholder="Ex: Ureia Agrícola" required
                           value="<?= htmlspecialchars((string)($produto['nome'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao" placeholder="Breve descrição do produto..."><?= htmlspecialchars((string)($produto['descricao'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="categoria">Categoria <span class="req">*</span></label>
                        <select id="categoria" name="categoria" required>
                            <option value="" disabled <?= empty($produto['categoria']) ? 'selected' : '' ?>>Selecione</option>
                            <option value="Fertilizante" <?= ($produto['categoria'] ?? '') === 'Fertilizante' ? 'selected' : '' ?>>Fertilizante</option>
                            <option value="Defensivo" <?= ($produto['categoria'] ?? '') === 'Defensivo' ? 'selected' : '' ?>>Defensivo</option>
                            <option value="Semente" <?= ($produto['categoria'] ?? '') === 'Semente' ? 'selected' : '' ?>>Semente</option>
                            <option value="Insumo" <?= ($produto['categoria'] ?? '') === 'Insumo' ? 'selected' : '' ?>>Insumo</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ncm">NCM <span class="req">*</span> <span class="hint">8 dígitos</span></label>
                        <input type="text" id="ncm" name="ncm" placeholder="00000000" maxlength="8" required
                               value="<?= htmlspecialchars((string)($produto['ncm'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>

                <div class="form-row-3">
                    <div class="form-group">
                        <label for="preco">Preço unit. <span class="req">*</span></label>
                        <div class="input-wrapper">
                            <span class="prefix">R$</span>
                            <input type="number" id="preco" name="preco" placeholder="0,00" min="0" step="0.01" required
                                   value="<?= htmlspecialchars((string)($produto['preco'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="estoque">Estoque <span class="req">*</span></label>
                        <input type="number" id="estoque" name="estoque" placeholder="0" min="0" step="0.01" required
                               value="<?= htmlspecialchars((string)($produto['estoque'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <div class="form-group">
                        <label for="unidade">Unidade <span class="req">*</span></label>
                        <select id="unidade" name="unidade" required>
                            <option value="" disabled <?= empty($produto['unidade']) ? 'selected' : '' ?>>—</option>
                            <option value="kg" <?= ($produto['unidade'] ?? '') === 'kg' ? 'selected' : '' ?>>kg</option>
                            <option value="T" <?= ($produto['unidade'] ?? '') === 'T' ? 'selected' : '' ?>>T</option>
                            <option value="un" <?= ($produto['unidade'] ?? '') === 'un' ? 'selected' : '' ?>>un</option>
                            <option value="L" <?= ($produto['unidade'] ?? '') === 'L' ? 'selected' : '' ?>>L</option>
                        </select>
                    </div>
                </div>

                <div class="section-divider">
                    <div class="section-divider-line"></div>
                    <div class="section-divider-label">Status</div>
                    <div class="section-divider-line"></div>
                </div>

                <div class="form-group">
                    <label>Produto ativo? <span class="req">*</span></label>
                    <div class="toggle-group">
                        <label class="toggle-option">
                            <input type="radio" name="ativo" value="1" <?= $ativo ? 'checked' : '' ?>>
                            <div class="toggle-option-dot"></div> Ativo
                        </label>
                        <label class="toggle-option">
                            <input type="radio" name="ativo" value="0" <?= !$ativo ? 'checked' : '' ?>>
                            <div class="toggle-option-dot"></div> Inativo
                        </label>
                    </div>
                </div>

                <div class="btn-row">
                    <a href="produtos.php" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">Salvar alterações →</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
