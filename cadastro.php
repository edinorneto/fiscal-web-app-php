<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto — Cadastro Fiscal PHP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page-header">
        <a class="nav-back" href="index.php">Menu principal</a>
        <div class="eyebrow">Novo produto</div>
        <h1>Cadastrar <span>Produto</span></h1>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-header-left">
                <div class="dot"></div>
                <h2>Dados do Produto</h2>
            </div>
        </div>
        <div class="card-body">
            <form action="process_cadastro.php" method="post">
                <div class="form-group">
                    <label for="nome">Nome do produto <span class="req">*</span></label>
                    <input type="text" id="nome" name="nome" placeholder="Ex: Ureia Agrícola" required>
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao" placeholder="Breve descrição do produto..."></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="categoria">Categoria <span class="req">*</span></label>
                        <select id="categoria" name="categoria" required>
                            <option value="" disabled selected>Selecione</option>
                            <option>Fertilizante</option>
                            <option>Defensivo</option>
                            <option>Semente</option>
                            <option>Insumo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ncm">NCM <span class="req">*</span> <span class="hint">8 dígitos</span></label>
                        <input type="text" id="ncm" name="ncm" placeholder="00000000" maxlength="8" required>
                    </div>
                </div>
                <div class="form-row-3">
                    <div class="form-group">
                        <label for="preco">Preço unit. <span class="req">*</span></label>
                        <div class="input-wrapper">
                            <span class="prefix">R$</span>
                            <input type="number" id="preco" name="preco" placeholder="0,00" min="0" step="0.01" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="estoque">Estoque <span class="req">*</span></label>
                        <input type="number" id="estoque" name="estoque" placeholder="0" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="unidade">Unidade <span class="req">*</span></label>
                        <select id="unidade" name="unidade" required>
                            <option value="" disabled selected>—</option>
                            <option>kg</option>
                            <option>T</option>
                            <option>un</option>
                            <option>L</option>
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
                            <input type="radio" name="ativo" value="1" checked>
                            <div class="toggle-option-dot"></div> Ativo
                        </label>
                        <label class="toggle-option">
                            <input type="radio" name="ativo" value="0">
                            <div class="toggle-option-dot"></div> Inativo
                        </label>
                    </div>
                </div>
                <div class="btn-row">
                    <a href="index.php" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">Salvar produto →</button>
                </div>
            </form>
        </div>
    </div>
    <footer class="page-footer">
        Projeto de estudo · <a href="https://linkedin.com/in/edinor-de-souza-neto">Edinor de Souza Neto</a> · PHP
    </footer>
</body>
</html>
