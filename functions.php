<?php

function gerarConteudoPHP($campos, $tipos, $nullos, $nomeTabela) {
    $conteudoArquivo = "<?php\n\n";
    $conteudoArquivo .= "\$campos = array(\n";

    for ($i = 0; $i < count($campos); $i++) {
        $campo = $campos[$i];
        $tipo = $tipos[$i];
        $nullable = in_array($i, $nullos) ? ' NULL' : '';

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
    $conteudoArquivo .= "{$nomeBanco} = pymysql.connect(host='localhost', user='root', password='', database='{$nomeBanco}')\n";
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





function SelectOptions($name) {
    $options = array(
        "INT",
        "INT PRIMARY KEY",
        "VARCHAR(255)",
        "TEXT",
        "DATE",
        "DATETIME",
        "FLOAT",
        "DOUBLE",
        "DECIMAL",
        "BOOL",
        "TINYINT",
        "SMALLINT",
        "MEDIUMINT",
        "BIGINT",
        "ENUM('value1', 'value2', ...)"
    );

    $select = "<select class='form-control' name='$name' required>\n";
    foreach ($options as $option) {
        $select .= "<option value='$option'>$option</option>\n";
    }
    $select .= "</select>\n";

    return $select;
}
?>
