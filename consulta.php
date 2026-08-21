<?php
require_once 'data.php';
require_once 'config.php';

$produtos = array_values(array_filter(
    carregar_produtos(ARQUIVO_JSON),
    fn($produto) => !empty($produto['ativo'])
));
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta Fiscal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="page-header">
        <a href="index.php" class="nav-back">Menu principal</a>
        <div class="eyebrow">Consulta fiscal</div>
        <h1>Tributos para <span>Nota Fiscal</span></h1>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-header-left">
                <div class="dot"></div>
                <h2>Parâmetros da consulta</h2>
            </div>
        </div>

        <div class="card-body">
            <form action="process_consulta.php" method="post">

                <div class="form-group">
                    <label>Produto <span class="req">*</span></label>
                    <select name="produto" required>
                        <option value="" disabled selected>Selecione um produto</option>
                        <?php foreach ($produtos as $p): ?>
                            <option value="<?= htmlspecialchars((string)($p['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars((string)($p['nome'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Regime tributário <span class="req">*</span></label>
                    <select name="regime" required>
                        <option value="" disabled selected>Selecione o regime</option>
                        <option value="ttd xx">TTD XX</option>
                        <option value="convenio xx">Convênio XX</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tipo de venda <span class="req">*</span></label>
                    <select name="regiao" id="sel-regiao" required>
                        <option value="" disabled selected>Selecione</option>
                        <option value="interna/sc">Interna (SC)</option>
                        <option value="externa">Externa</option>
                    </select>
                </div>

                <div class="form-group" id="grupo-estado">
                    <label>Estado de destino <span class="hint">Somente para venda externa</span></label>
                    <select name="estado" id="sel-estado">
                        <option value="" disabled selected>Selecione o estado</option>
                        <option value="pr">PR</option>
                        <option value="rs">RS</option>
                        <option value="mt">MT</option>
                        <option value="ms">MS</option>
                    </select>
                </div>

                <div class="btn-row">
                    <a href="index.php" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">Calcular tributos →</button>
                </div>

            </form>
        </div>
    </div>

    <footer class="page-footer">
        Projeto de estudo · <a href="https://www.linkedin.com/in/edinor-de-souza-neto/" target="_blank" rel="noopener noreferrer">Edinor de Souza Neto</a> · PHP
    </footer>

    <script>
        const regiaoSel  = document.getElementById('sel-regiao');
        const grupoEstado = document.getElementById('grupo-estado');
        const estadoSel  = document.getElementById('sel-estado');

        function syncEstado() {
            const externo = regiaoSel.value === 'externa';
            grupoEstado.style.display = externo ? '' : 'none';
            estadoSel.required        = externo;
            if (!externo) estadoSel.value = '';
        }

        regiaoSel.addEventListener('change', syncEstado);
        syncEstado(); // estado inicial correto sem interação
    </script>

</body>
</html>
