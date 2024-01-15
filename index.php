<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de Tabelas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h2 {
            color: #007bff;
        }

        label {
            font-weight: bold;
        }

        .btn {
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h2>Gerador de Tabelas</h2>

        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nomeTabela = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nome']);

            if (empty($nomeTabela)) {
                echo "Nome de tabela inválido.";
                exit;
            }

            $diretorio = "./geradores/";

            if (!file_exists($diretorio)) {
                mkdir($diretorio, 0777, true);
            }

            $tipoArquivo = isset($_POST['tipo_arquivo']) ? $_POST['tipo_arquivo'] : '';
            $extensao = ($tipoArquivo === 'php') ? 'php' : 'sql';

            $conteudoArquivo = "";

            if ($tipoArquivo === 'php') {
                $conteudoArquivo .= "<?php\n\n";
                $conteudoArquivo .= "\$campos = array(\n";

                if (isset($_POST['campos']) && isset($_POST['tipos'])) {
                    $campos = $_POST['campos'];
                    $tipos = $_POST['tipos'];
                    $nullos = isset($_POST['nullos']) ? $_POST['nullos'] : [];

                    for ($i = 0; $i < count($campos); $i++) {
                        $campo = $campos[$i];
                        $tipo = $tipos[$i];
                        $nullable = in_array($i, $nullos) ? ' NULL' : '';

                        $conteudoArquivo .= "    '{$campo} {$tipo}{$nullable}',\n";
                    }
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
            } elseif ($tipoArquivo === 'sql') {
                $conteudoArquivo .= "-- SQL para criar a tabela\n";
                $conteudoArquivo .= "CREATE TABLE IF NOT EXISTS $nomeTabela (\n";

                if (isset($_POST['campos']) && isset($_POST['tipos'])) {
                    $campos = $_POST['campos'];
                    $tipos = $_POST['tipos'];
                    $nullos = isset($_POST['nullos']) ? $_POST['nullos'] : [];

                    for ($i = 0; $i < count($campos); $i++) {
                        $campo = $campos[$i];
                        $tipo = $tipos[$i];
                        $nullable = in_array($i, $nullos) ? ' NULL' : '';

                        $conteudoArquivo .= "    {$campo} {$tipo}{$nullable},\n";
                    }
                }

                // Adiciona automaticamente os campos de timestamp
                $conteudoArquivo .= "    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\n";
                $conteudoArquivo .= "    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP\n";

                $conteudoArquivo .= ");";
            }

            $nomeArquivo = "{$diretorio}{$nomeTabela}.{$extensao}";

            if (file_put_contents($nomeArquivo, $conteudoArquivo) !== false) {
                echo "Arquivo gerado com sucesso: $nomeArquivo";
                echo "Abrir arquivo na pasta <abrir>";
            } else {
                echo "Erro ao salvar o arquivo.";
            }
        }
        ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="nomeTabela">Nome da Tabela:</label>
                <input type="text" class="form-control" id="nomeTabela" name="nome" required>
            </div>

            <div class="form-group">
                <label for="tipoArquivo">Tipo de Arquivo:</label>
                <select class="form-control" name="tipo_arquivo" required>
                    <option value="php">PHP</option>
                    <option value="sql">SQL</option>
                </select>
            </div>

            <div id="camposContainer">
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <label for="campos">Campo:</label>
                            <input type="text" class="form-control" name="campos[]" required>
                        </div>
                        <div class="col">
                            <label for="tipos">Tipo:</label>
                            <select class="form-control" name="tipos[]" required>
                                <option value="INT">INT</option>
                                <option value="INT PRIMARY KEY">INT PRIMARY KEY</option>
                                <option value="VARCHAR(255)">VARCHAR(255)</option>
                                <option value="TEXT">TEXT</option>
                                <option value="DATE">DATE</option>
                                <option value="DATETIME">DATETIME</option>
                                <option value="FLOAT">FLOAT</option>
                                <option value="DOUBLE">DOUBLE</option>
                                <option value="DECIMAL">DECIMAL</option>
                                <option value="BOOL">BOOL</option>
                                <option value="TINYINT">TINYINT</option>
                                <option value="SMALLINT">SMALLINT</option>
                                <option value="MEDIUMINT">MEDIUMINT</option>
                                <option value="BIGINT">BIGINT</option>
                                <option value="ENUM('value1', 'value2', ...)">ENUM</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="nullos">Vazio:    </label>
                            <input type="checkbox" class="form-check-input" name="nullos[]">
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-primary" onclick="adicionarCampo()"><i class="fas fa-plus"></i> Adicionar Campo</button>
            <button type="submit" class="btn btn-success"><i class="fas fa-table"></i> Gerar Tabela</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>

    <script>
        function adicionarCampo() {
            const container = document.getElementById('camposContainer');
            const novoCampo = document.createElement('div');
            novoCampo.innerHTML = `
            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <label for="campos">Campo:</label>
                        <input type="text" class="form-control" name="campos[]" required>
                    </div>
                    <div class="col">
                        <label for="tipos">Tipo:</label>
                        <select class="form-control" name="tipos[]" required>
                            <option value="INT">INT</option>
                            <option value="INT PRIMARY KEY">INT PRIMARY KEY</option>
                            <option value="VARCHAR(255)">VARCHAR(255)</option>
                            <option value="TEXT">TEXT</option>
                            <option value="DATE">DATE</option>
                            <option value="DATETIME">DATETIME</option>
                            <option value="FLOAT">FLOAT</option>
                            <option value="DOUBLE">DOUBLE</option>
                            <option value="DECIMAL">DECIMAL</option>
                            <option value="BOOL">BOOL</option>
                            <option value="TINYINT">TINYINT</option>
                            <option value="SMALLINT">SMALLINT</option>
                            <option value="MEDIUMINT">MEDIUMINT</option>
                            <option value="BIGINT">BIGINT</option>
                            <option value="ENUM('value1', 'value2', ...)">ENUM</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="nullos">Vazio:    </label>
                        <input type="checkbox" class="form-check-input" name="nullos[]">
                    </div>
                </div>
            </div>
        `;
            container.append(novoCampo);
        }
    </script>

</body>

</html>
