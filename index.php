<?php
require_once('tabelas_functions.php');
?>

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

        #nomeBancoContainer {
            display: none;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h2>Gerador de Tabelas</h2>

        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nomeTabela = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nome']);
            $nomeBanco = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nome_banco']);

            if (empty($nomeTabela) || (isset($_POST['tipo_arquivo']) && $_POST['tipo_arquivo'] === 'python' && empty($nomeBanco))) {
                echo "Nome de tabela ou banco inválido.";
                exit;
            }

            $diretorio = "./tabelas/";

            if (!file_exists($diretorio)) {
                mkdir($diretorio, 0777, true);
            }

            $tiposArquivos = array(
                'php' => 'php',
                'sql' => 'sql',
                'python' => 'py',
            );

            $tipoArquivo = isset($_POST['tipo_arquivo']) ? $_POST['tipo_arquivo'] : '';
            $extensao = isset($tiposArquivos[$tipoArquivo]) ? $tiposArquivos[$tipoArquivo] : '';

            $conteudoArquivo = "";

        

			if ($tipoArquivo === 'php') {
				$conteudoArquivo .= gerarConteudoPHP($_POST['campos'], $_POST['tipos'], isset($_POST['nullos']) ? $_POST['nullos'] : [], $nomeTabela);
			} elseif ($tipoArquivo === 'sql') {
				$conteudoArquivo .= gerarConteudoSQL($_POST['campos'], $_POST['tipos'], isset($_POST['nullos']) ? $_POST['nullos'] : [], $nomeTabela);
			} elseif ($tipoArquivo === 'python') {
				$conteudoArquivo .= gerarConteudoPython($_POST['campos'], $_POST['tipos'], isset($_POST['nullos']) ? $_POST['nullos'] : [], $nomeTabela, $nomeBanco);
			}


            $nomeArquivo = "{$diretorio}{$nomeTabela}.{$extensao}";

         if (file_put_contents($nomeArquivo, $conteudoArquivo) !== false) {
			$diretorioArquivo = dirname($nomeArquivo);
			echo "<div class='alert alert-success'>Arquivo gerado com sucesso!</div>";
			echo "<p>Abra o arquivo na pasta: <a href='$diretorioArquivo' target='_blank'>$diretorioArquivo</a></p>";
		} else {
			echo "<div class='alert alert-danger'>Erro ao salvar o arquivo.</div>";
		}



        }
        ?>

        <form action="" method="post">
		
			 <div class="form-group">
                <label for="tipoArquivo">Tipo de Arquivo:</label>
                <select class="form-control" name="tipo_arquivo" id="tipoArquivo" required>
                    <option value="php">PHP</option>
                    <option value="sql">SQL</option>
                    <option value="python">Python</option>
                </select>
            </div>
			
            <div class="form-group">
                <label for="nomeTabela">Nome da Tabela:</label>
                <input type="text" class="form-control" id="nomeTabela" name="nome" required>
            </div>

            <div class="form-group" id="nomeBancoContainer">
                <label for="nomeBanco">Nome do Banco:</label>
                <input type="text" class="form-control" id="nomeBanco" name="nome_banco">
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
                            <?php echo SelectOptions('tipos[]'); ?>
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
                        <?php echo SelectOptions('tipos[]'); ?>
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

        document.getElementById('tipoArquivo').addEventListener('change', function () {
            const nomeBancoContainer = document.getElementById('nomeBancoContainer');
            nomeBancoContainer.style.display = this.value === 'python' ? 'block' : 'none';
        });
    </script>

</body>

</html>
