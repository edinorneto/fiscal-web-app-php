<?php

function carregar_produtos($caminho) {
    if (!file_exists($caminho)) {
        return [];
    }

    $json = @file_get_contents($caminho);

    if ($json === false) {
        return [];
    }

    $dados = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return [];
    }

    return $dados;
}

function salvar_produtos($caminho, $produtos) {
    $json = json_encode($produtos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    if (@file_put_contents($caminho, $json) === false) {
        return false;
    }

    return true;
}

function atualizar_produto($arquivo, $id, $novos_dados) {
    $dados = carregar_produtos($arquivo);

    foreach ($dados as $i => $produto) {
        if ((int)$id === (int)($produto['id'] ?? 0)) {

            $produto_atualizado = $produto;

            foreach ($novos_dados as $campo => $valor) {
                if ($campo === 'id' || $campo === 'data_cadastro') continue;
                if (trim((string)$valor) === '') continue;

                if ($campo === 'preco' || $campo === 'estoque') {
                    if (!is_numeric($valor)) continue;
                    $produto_atualizado[$campo] = floatval($valor);
                    continue;
                }

                $produto_atualizado[$campo] = trim((string)$valor);
            }

            $dados[$i] = $produto_atualizado;
            salvar_produtos($arquivo, $dados);
            return $produto_atualizado;
        }
    }

    return false;
}

function alternar_status($arquivo, $id) {
    $dados = carregar_produtos($arquivo);

    foreach ($dados as $i => $produto) {
        if ((int)$id === (int)($produto['id'] ?? 0)) {
            $produto_atualizado = $produto;

            $ativo_atual = !empty($produto_atualizado['ativo']) ? 1 : 0;

            $produto_atualizado['ativo'] = $ativo_atual ? 0 : 1;

            $dados[$i] = $produto_atualizado;
            salvar_produtos($arquivo, $dados);
            return $produto_atualizado;
        }
    }

    return false;
}

function apagar_produto($arquivo, $id) {
    $dados = carregar_produtos($arquivo);

    foreach ($dados as $i => $produto) {
        if ((int)$id === (int)($produto['id'] ?? 0)) {
            $produto_apagado = $produto;

            unset($dados[$i]);
            $dados = array_values($dados);

            salvar_produtos($arquivo, $dados);
            return $produto_apagado;
        }
    }

    return false;
}

?>