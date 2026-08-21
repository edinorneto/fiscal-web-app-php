<?php
require_once 'config.php';
require_once 'data.php';
require_once 'tax_rules.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    header('Location: index.php');
    exit;
}

$produto_id = trim((string)($_POST['produto'] ?? ""));
$regime = trim((string)($_POST['regime'] ?? ""));
$regiao = trim((string)($_POST['regiao'] ?? ""));
$estado = trim((string)($_POST['estado'] ?? ""));

$erros = [];

// validações mínimas
if ($produto_id === "" || !ctype_digit($produto_id)) {
    $erros[] = "Selecione um produto válido.";
}
if ($regime === "") {
    $erros[] = "Selecione um regime.";
}
if ($regiao === "") {
    $erros[] = "Selecione o tipo de venda.";
} elseif (!in_array($regiao, ['interna/sc', 'externa'], true)) {
    $erros[] = "Tipo de venda inválido.";
}

if ($regiao === "externa") {
    if ($estado === "") {
        $erros[] = "Selecione o estado de destino para venda externa.";
    } elseif (!in_array($estado, ['pr', 'rs', 'mt', 'ms'], true)) {
        $erros[] = "Estado de destino inválido.";
    }
}

$regiao_chave = ($regiao === 'externa') ? ($regiao . "/" . $estado) : $regiao;

$resultado = [];
if (empty($erros)) {
    switch ($regime) {
        case 'ttd xx':
        case 'convenio xx':
            $resultado = obter_informacoes($regime, $regiao_chave);
            break;
        default:
            $erros[] = "Regime inválido.";
            $resultado = [];
            break;
    }
}

$produtos = carregar_produtos(ARQUIVO_JSON);
$produto_selecionado = null;

foreach ($produtos as $p) {
    if ((int)($p['id'] ?? 0) === (int)$produto_id) {
        $produto_selecionado = $p;
        break;
    }
}

if ($produto_selecionado === null) {
    $erros[] = "Produto não encontrado.";
} elseif (empty($produto_selecionado['ativo'])) {
    $erros[] = "Produto inativo não pode ser consultado.";
}

// defaults
$cfop      = $resultado['cfop']      ?? '-';
$cst       = $resultado['cst']       ?? '-';
$trib_icms = $resultado['trib_icms'] ?? '-';
$icms      = $resultado['icms']      ?? '-';
$ipi       = $resultado['ipi']       ?? '-';
$pis       = $resultado['pis']       ?? '-';
$cofins    = $resultado['cofins']    ?? '-';
$descricao = $resultado['descricao'] ?? '-';

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da Consulta</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="page-header">
        <a href="consulta.php" class="nav-back">Nova consulta</a>
        <div class="eyebrow">Resultado fiscal</div>
        <h1>Informações para <span>NF-e</span></h1>
    </div>

<?php if (!empty($erros)): ?>
    <div class="alert alert-error">
        <span class="alert-icon">✗</span>
        <div>
            <?php foreach ($erros as $erro): ?>
                <p><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endforeach; ?>
        </div>
    </div>
    <a href="consulta.php" class="btn-secondary">← Voltar</a>

<?php else: ?>
    <div class="card">
        <div class="card-header">
            <div class="card-header-left">
                <div class="dot dot-g"></div>
                <h2><?= htmlspecialchars((string)($produto_selecionado['nome'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></h2>
            </div>
            <span class="badge b-o"><?= htmlspecialchars(strtoupper($regime), ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <div class="result-section">
            <div class="result-section-title">1 · Dados do produto</div>
            <div class="result-table">
                <div class="result-row">
                    <span class="row-label">Nome</span>
                    <span class="row-value"><?= htmlspecialchars((string)($produto_selecionado['nome'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="result-row">
                    <span class="row-label">NCM</span>
                    <span class="row-value"><?= htmlspecialchars((string)($produto_selecionado['ncm'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="result-row">
                    <span class="row-label">Preço unitário</span>
                    <span class="row-value">R$ <?= number_format((float)($produto_selecionado['preco'] ?? 0), 2, ',', '.') ?></span>
                </div>
                <div class="result-row">
                    <span class="row-label">Estoque</span>
                    <span class="row-value">
                        <?= htmlspecialchars((string)($produto_selecionado['estoque'] ?? '-'), ENT_QUOTES, 'UTF-8') ?>
                        <?= htmlspecialchars((string)($produto_selecionado['unidade'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </div>
                <div class="result-row total">
                    <span class="row-label">Valor total</span>
                    <span class="row-value">
                        R$ <?= number_format(((float)($produto_selecionado['preco'] ?? 0)) * ((float)($produto_selecionado['estoque'] ?? 0)), 2, ',', '.') ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="result-section">
            <div class="result-section-title">2 · Dados fiscais</div>
            <div class="result-table">
                <div class="result-row">
                    <span class="row-label">CFOP</span>
                    <span class="row-value"><?= htmlspecialchars((string)$cfop, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="result-row">
                    <span class="row-label">CST</span>
                    <span class="row-value"><?= htmlspecialchars((string)$cst, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="result-row">
                    <span class="row-label">Tributação ICMS</span>
                    <span class="row-value"><?= htmlspecialchars((string)$trib_icms, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="result-row">
                    <span class="row-label">Destino</span>
                    <span class="row-value"><?= htmlspecialchars(strtoupper($regiao_chave), ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
        </div>

        <div class="result-section">
            <div class="result-section-title">3 · Alíquotas</div>
            <div class="tax-grid">
                <div class="tax-cell">
                    <div class="tax-cell-label">ICMS</div>
                    <div class="tv <?= ($icms !== '0' && $icms !== '-') ? 'hi' : 'zero' ?>"><?= htmlspecialchars((string)$icms, ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="tax-cell-unit">%</div>
                </div>
                <div class="tax-cell">
                    <div class="tax-cell-label">IPI</div>
                    <div class="tax-cell-value zero"><?= htmlspecialchars((string)$ipi, ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="tax-cell-unit">%</div>
                </div>
                <div class="tax-cell">
                    <div class="tax-cell-label">PIS</div>
                    <div class="tax-cell-value zero"><?= htmlspecialchars((string)$pis, ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="tax-cell-unit">%</div>
                </div>
                <div class="tax-cell">
                    <div class="tax-cell-label">COFINS</div>
                    <div class="tax-cell-value zero"><?= htmlspecialchars((string)$cofins, ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="tax-cell-unit">%</div>
                </div>
            </div>
        </div>

        <div class="result-section">
            <div class="result-section-title">4 · Descrição legal</div>
            <div class="legal-box"><?= htmlspecialchars((string)$descricao, ENT_QUOTES, 'UTF-8') ?></div>
        </div>

        <div class="result-section">
            <div class="btn-row">
                <a href="consulta.php" class="btn-secondary">← Nova consulta</a>
                <a href="index.php" class="btn-primary">Menu principal</a>
            </div>
        </div>
    </div>
<?php endif; ?>

    <footer class="page-footer">
        Projeto de estudo · <a href="https://www.linkedin.com/in/edinor-de-souza-neto/" target="_blank" rel="noopener noreferrer">Edinor de Souza Neto</a> · PHP
    </footer>

</body>
</html>