<?php

// CRIADO POR CARLOS TMJ & CHAT GPT FREE

function gerarConteudoPHP($campos, $tipos, $nullos, $nomeTabela) {
    $conteudoArquivo = "<?php\n\n";

    // Adiciona a conexão diretamente no conteúdo
    $conteudoArquivo .= "\$conexao = new mysqli(";

    // Verifica se a sessão existe e se há dados suficientes
    if (isset($_SESSION['bancoDeDados']) && count($_SESSION['bancoDeDados']) === 4) {
        $conteudoArquivo .= "'".$_SESSION['bancoDeDados']['servidor']."', ";
        $conteudoArquivo .= "'".$_SESSION['bancoDeDados']['usuario']."', ";
        $conteudoArquivo .= "'".$_SESSION['bancoDeDados']['senha']."', ";
        $conteudoArquivo .= "'".$_SESSION['bancoDeDados']['nomeBanco']."');\n";
    } else {
        // Se a sessão não existir ou não contiver dados suficientes, use valores padrão
        $conteudoArquivo .= "'localhost', 'root', '', 'test');\n";
    }

    $conteudoArquivo .= "// Verifica se a conexão foi estabelecida\n";
    $conteudoArquivo .= "if (\$conexao->connect_error) {\n";
    $conteudoArquivo .= "    die('Erro na conexão: ' . \$conexao->connect_error);\n";
    $conteudoArquivo .= "}\n\n";

    $conteudoArquivo .= "\$campos = array(\n";

    for ($i = 0; $i < count($campos); $i++) {
        $campo = $campos[$i];
        $tipo = $tipos[$i];
        $nullable = in_array($i, $nullos) ? ' NULL' : '';

        // Verifica se é ENUM e ajusta a formatação correta dos valores
        if (strpos($tipo, 'ENUM') !== false) {
            $enumValues = array();
            preg_match_all("/'([^']+)'/", $campo, $matches);
            if (!empty($matches[1])) {
                $enumValues = $matches[1];
            }
            $tipo = "ENUM('" . implode("', '", $enumValues) . "')";
        }

        $conteudoArquivo .= "    '{$campo} {$tipo}{$nullable}',\n";
    }

    // Adiciona automaticamente os campos de timestamp
    $conteudoArquivo .= "    'data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP',\n";
    $conteudoArquivo .= "    'data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'\n";

    $conteudoArquivo .= ");\n\n";
    $conteudoArquivo .= "// SQL para criar a tabela\n";
    $conteudoArquivo .= "\$sqlCreateTable = \"CREATE TABLE IF NOT EXISTS $nomeTabela (\" . implode(', ', \$campos) . \")\";\n\n";

    $conteudoArquivo .= "// Executamos a query\n";
    $conteudoArquivo .= "if (\$conexao->query(\$sqlCreateTable) === TRUE) {\n";
    $conteudoArquivo .= "    echo \"Tabela $nomeTabela criada com sucesso!\\n\";\n";
    $conteudoArquivo .= "} else {\n";
    $conteudoArquivo .= "    echo \"Erro ao criar tabela: \" . \$conexao->error . \"\\n\";\n";
    $conteudoArquivo .= "}\n";

    $conteudoArquivo .= "// Fecha a conexão\n";
    $conteudoArquivo .= "\$conexao->close();\n";

    $conteudoArquivo .= "?>";

    return $conteudoArquivo;
}




function gerarConteudoSQL($campos, $tipos, $nullos, $nomeTabela) {
    $conteudoArquivo = "-- SQL para criar a tabela\n";
    $conteudoArquivo .= "CREATE TABLE IF NOT EXISTS $nomeTabela (\n";

    for ($i = 0; $i < count($campos); $i++) {
        $campo = $campos[$i];
        $tipo = $tipos[$i];
        $nullable = in_array($i, $nullos) ? ' NULL' : '';

        $conteudoArquivo .= "    {$campo} {$tipo}{$nullable},\n";
    }

    // Adiciona automaticamente os campos de timestamp
    $conteudoArquivo .= "    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\n";
    $conteudoArquivo .= "    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP\n";

    $conteudoArquivo .= ");";

    return $conteudoArquivo;
}


# Função para gerar o conteúdo Python
function gerarConteudoPython($campos, $tipos, $nullos, $nomeTabela, $nomeBanco)
{
    $conteudoArquivo = "# Código Python para criar a tabela\n";
    $conteudoArquivo .= "import pymysql\n\n";

    // Verifica se a sessão existe e contém dados suficientes
    if (isset($_SESSION['bancoDeDados']) && count($_SESSION['bancoDeDados']) === 4) {
        $conteudoArquivo .= "{$_SESSION['bancoDeDados']['nomeBanco']} = pymysql.connect(host='{$_SESSION['bancoDeDados']['servidor']}', ";
        $conteudoArquivo .= "user='{$_SESSION['bancoDeDados']['usuario']}', ";
        $conteudoArquivo .= "password='{$_SESSION['bancoDeDados']['senha']}', ";
        $conteudoArquivo .= "database='{$_SESSION['bancoDeDados']['nomeBanco']}')\n";
    } else {
        // Se a sessão não existir ou não conter dados suficientes, use valores padrão
        $conteudoArquivo .= "{$nomeBanco} = pymysql.connect(host='localhost', user='root', password='', database='{$nomeBanco}')\n";
    }

    $conteudoArquivo .= "cursor = {$nomeBanco}.cursor()\n\n";
    $conteudoArquivo .= "query = f'''CREATE TABLE IF NOT EXISTS {$nomeTabela} (\n";

    for ($i = 0; $i < count($campos); $i++) {
        $campo = $campos[$i];
        $tipo = $tipos[$i];
        $nullable = in_array($i, $nullos) ? ' NULL' : '';

        $conteudoArquivo .= "    {$campo} {$tipo}{$nullable},\n";
    }

    // Adiciona automaticamente os campos de timestamp
    $conteudoArquivo .= "    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\n";
    $conteudoArquivo .= "    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP\n";

    $conteudoArquivo .= ")'''\n";
    $conteudoArquivo .= "try:\n";
    $conteudoArquivo .= "    cursor.execute(query)\n";
    $conteudoArquivo .= "    {$nomeBanco}.commit()\n";
    $conteudoArquivo .= "    print('Tabela criada com sucesso!')\n";
    $conteudoArquivo .= "except Exception as e:\n";
    $conteudoArquivo .= "    print(f'Erro ao criar tabela: {e}')\n";
    $conteudoArquivo .= "    input('Pressione Enter para fechar...')\n";
    $conteudoArquivo .= "finally:\n";
    $conteudoArquivo .= "    cursor.close()\n";
    $conteudoArquivo .= "{$nomeBanco}.close()\n";

    // Adiciona um loop infinito para manter o script em execução
    $conteudoArquivo .= "while True:\n";
    $conteudoArquivo .= "    pass\n";
    
    return $conteudoArquivo;
}






?>
