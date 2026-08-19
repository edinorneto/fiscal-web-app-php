<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Fiscal PHP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="page-header-wide">
        <div class="eyebrow">Simulador Fiscal · PHP</div>
        <h1>Cadastro Fiscal <span>PHP</span></h1>
        <p class="subtitle">
            Cadastre produtos e consulte tributos para emissão de NF-e.<br>
            Dados sintéticos — somente para fins educacionais.
        </p>
    </header>

    <main class="menu-grid-3">

        <a href="cadastro.php" class="menu-card">
            <div>
                <span class="menu-card-icon">📦</span>
                <h2 class="menu-card-title">Cadastrar Produto</h2>
                <p class="menu-card-desc">
                    Registre nome, NCM, preço, estoque e categoria. Os dados ficam salvos em JSON.
                </p>
            </div>
            <div class="menu-card-arrow">Acessar &rarr;</div>
        </a>

        <a href="consulta.php" class="menu-card">
            <div>
                <span class="menu-card-icon">📄</span>
                <h2 class="menu-card-title">Consultar Tributos</h2>
                <p class="menu-card-desc">
                    Selecione o produto, regime tributário e destino para obter os dados da NF-e.
                </p>
            </div>
            <div class="menu-card-arrow">Acessar &rarr;</div>
        </a>

        <a href="produtos.php" class="menu-card">
            <div>
                <span class="menu-card-icon">🗂️</span>
                <h2 class="menu-card-title">Gerenciar Produtos</h2>
                <p class="menu-card-desc">
                    Visualize a lista, edite, ative/inative e apague produtos cadastrados.
                </p>
            </div>
            <div class="menu-card-arrow">Acessar &rarr;</div>
        </a>

    </main>

    <footer class="page-footer">
        <p>Projeto de estudo · <a href="https://www.linkedin.com/in/edinor-de-souza-neto/" target="_blank">Edinor de Souza Neto</a> · PHP 8.x</p>
    </footer>

</body>
</html>
