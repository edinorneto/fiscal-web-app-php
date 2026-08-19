<?php

function carregar_produtos($caminho) {
    if (!file_exists($caminho)) {
        return [];
    }

    $fp = @fopen($caminho, 'rb');
    if ($fp === false) {
        error_log("[data.php] carregar_produtos: falha ao abrir arquivo para leitura: {$caminho}");
        return [];
    }

    // Shared lock para leitura consistente enquanto houver uma gravação exclusiva
    if (!flock($fp, LOCK_SH)) {
        error_log("[data.php] carregar_produtos: falha ao obter shared lock em: {$caminho}");
        fclose($fp);
        return [];
    }

    $json = stream_get_contents($fp);
    flock($fp, LOCK_UN);
    fclose($fp);

    if ($json === false) {
        error_log("[data.php] carregar_produtos: falha ao ler conteúdo de: {$caminho}");
        return [];
    }

    $dados = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("[data.php] carregar_produtos: json decode error ({$caminho}): " . json_last_error_msg());
        return [];
    }

    return $dados;
}

function salvar_produtos($caminho, $produtos) {
    $json = json_encode($produtos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        error_log("[data.php] salvar_produtos: falha ao json_encode: " . json_last_error_msg());
        return false;
    }

    $dir = dirname($caminho);

    // Gera backup do arquivo existente (se existir)
    if (file_exists($caminho)) {
        $backup = $caminho . '.bak.' . date('YmdHis');
        if (!@copy($caminho, $backup)) {
            error_log("[data.php] salvar_produtos: falha ao criar backup {$backup} a partir de {$caminho}");
            // Não abortamos — tentamos prosseguir com a gravação, mas registramos o problema.
        } else {
            error_log("[data.php] salvar_produtos: backup criado em {$backup}");
        }
    }

    // Escreve em um arquivo temporário no mesmo diretório para garantir rename atômico
    $tmp = tempnam($dir, 'prod_');
    if ($tmp === false) {
        error_log("[data.php] salvar_produtos: falha ao criar arquivo temporário em {$dir}");
        return false;
    }

    $fp = @fopen($tmp, 'wb');
    if ($fp === false) {
        error_log("[data.php] salvar_produtos: falha ao abrir temporário para escrita: {$tmp}");
        @unlink($tmp);
        return false;
    }

    // Exclusive lock durante a escrita no temporário (reduz chances de concorrência local)
    if (!flock($fp, LOCK_EX)) {
        error_log("[data.php] salvar_produtos: falha ao obter exclusive lock em temporário: {$tmp}");
        fclose($fp);
        @unlink($tmp);
        return false;
    }

    $bytes = fwrite($fp, $json);
    if ($bytes === false) {
        error_log("[data.php] salvar_produtos: falha ao escrever JSON no temporário: {$tmp}");
        flock($fp, LOCK_UN);
        fclose($fp);
        @unlink($tmp);
        return false;
    }

    // Garantir que tudo foi gravado no disco
    if (!fflush($fp)) {
        error_log("[data.php] salvar_produtos: fflush falhou para: {$tmp}");
        flock($fp, LOCK_UN);
        fclose($fp);
        @unlink($tmp);
        return false;
    }

    flock($fp, LOCK_UN);
    fclose($fp);

    // Renomeia temporário para o arquivo final (operação atômica na maioria dos sistemas)
    if (!@rename($tmp, $caminho)) {
        error_log("[data.php] salvar_produtos: falha ao renomear {$tmp} para {$caminho}");
        @unlink($tmp);
        return false;
    }

    // Ajusta permissões para que o servidor web possa ler/escrever conforme esperado (tentativa segura)
    @chmod($caminho, 0664);

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
            if (!salvar_produtos($arquivo, $dados)) {
                error_log("[data.php] atualizar_produto: falha ao salvar produto atualizado (id={$id}) em {$arquivo}");
                return false;
            }
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
            if (!salvar_produtos($arquivo, $dados)) {
                error_log("[data.php] alternar_status: falha ao salvar status alterado (id={$id}) em {$arquivo}");
                return false;
            }
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

            if (!salvar_produtos($arquivo, $dados)) {
                error_log("[data.php] apagar_produto: falha ao salvar após apagar produto (id={$id}) em {$arquivo}");
                return false;
            }
            return $produto_apagado;
        }
    }

    return false;
}

?>