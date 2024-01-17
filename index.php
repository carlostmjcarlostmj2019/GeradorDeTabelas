<?php
	require_once('tabelas_functions.php');
	require_once('tabelas_config.php');
	
	$resultado1 = '';
	$resultado2 = '';
	
	// Verifica qual formulário foi enviado
	if ($_SERVER["REQUEST_METHOD"] === "POST") {
	    if (isset($_POST['configServer'])) {
	        // Processar o formulário de configuração do servidor
	        $db_server = $_POST['serverInput'];
	        $db_user = $_POST['userInput'];
	        $db_senha = $_POST['passwordInput'];
	        $db_nome = $_POST['dbNameInput'];
	
	        // Chama a função para inserir ou obter dados do banco de dados na sessão
	        setarDadosSessaoBanco($db_server, $db_user, $db_senha, $db_nome);
	    } elseif (isset($_POST['gerarTabela'])) {
	        // Processar o formulário de geração de tabela
	        $nomeTabela = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nome']);
	        $nomeBanco = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nome_banco']);
	
	        if (empty($nomeTabela) || (isset($_POST['tipo_arquivo']) && $_POST['tipo_arquivo'] === 'python' && empty($nomeBanco))) {
	            echo "Nome de tabela ou banco inválido.";
	            exit;
	        }
	
	        $diretorio = "./saida/";
	
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
	            $resultado1 = "Arquivo gerado com sucesso!";
	            $resultado2 = "<p>Abra o arquivo na pasta: <a href='$nomeArquivo' target='_blank'>Ver Arquivo</a></p>";
	        } else {
	            echo "<div class='alert alert-danger'>Erro ao salvar o arquivo.</div>";
	        }
	    }
	}
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
			.navbar {
			background-color: #343a40;
			}
			.navbar-dark .navbar-toggler-icon {
			background-color: #fff;
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
			#camposContainer {
			margin-top: 20px;
			}
			@media (max-width: 576px) {
			.navbar-toggler {
			margin-right: 0;
			}
			.navbar-nav {
			margin-top: 10px;
			}
			}
			.resultado {
			margin-top: 20px;
			padding: 10px;
			border: 1px solid #28a745;
			border-radius: 4px;
			background-color: #d4edda;
			color: #155724;
			}
			.infoss {
			margin-top: 20px;
			padding: 10px;
			border: 1px solid #28a745;
			border-radius: 4px;
			background-color: danger;
			color: 155724;
			}
			.checkbox-container {
			display: flex;
			align-items: center;
			}
			.checkbox-container label {
			margin-right: 8px;
			}
			.custom-checkbox {
			width: 16px;
			height: 16px;
			margin-top: 2px; /* Ajuste este valor conforme necessário para alinhar verticalmente */
			}
			
			.footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        background-color: #343a40;
        padding: 15px 0;
        text-align: center;
        color: #ffffff;
    }

    .social-icons {
        font-size: 24px;
        margin: 0 10px;
        color: #ffffff;
        transition: color 0.3s ease-in-out;
    }

    .social-icons:hover {
        color: #808080;
    }
		</style>
	</head>
	<body>
		<nav class="navbar navbar-expand-lg navbar-dark">
			<a class="navbar-brand" href="#">Gerador de Tabelas</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav"
				aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse justify-content-end" id="navbarNav">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="#" data-toggle="modal" data-target="#dadosBancoModal">
						<i class="fas fa-cogs"></i> Configurar Servidor
						</a>
					</li>
				</ul>
			</div>
		</nav>
		<div class="container mt-5">
			<h2>Gerador de Tabelas</h2>
			<!-- Conteúdo do Modal -->
			<div class="modal fade" id="dadosBancoModal" tabindex="-1" role="dialog" aria-labelledby="dadosBancoModalLabel"
				aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="dadosBancoModalLabel">Configurar Servidor</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<!-- Formulário para os dados do banco de dados -->
							<form method="post" name="configServer">
								<center>
									<h3> <i class="fas fa-cogs"></i> Configurar Servidor </h3>
								</center>
								<div class="form-group">
									<label for="serverInput">Servidor:</label>
									<input type="text" class="form-control" id="serverInput" name="serverInput" placeholder="Digite o servidor" value="<?php echo $_SESSION['bancoDeDados']['servidor'] ?? ''; ?>" required>
								</div>
								<div class="form-group">
									<label for="userInput">Usuário:</label>
									<input type="text" class="form-control" id="userInput" name="userInput" placeholder="Digite o usuário" value="<?php echo $_SESSION['bancoDeDados']['usuario'] ?? ''; ?>" required>
								</div>
								<div class="form-group">
									<label for="passwordInput">Senha:</label>
									<input type="password" class="form-control" id="passwordInput" name="passwordInput" placeholder="Digite a senha" value="<?php echo $_SESSION['bancoDeDados']['senha'] ?? ''; ?>">
								</div>
								<div class="form-group">
									<label for="dbNameInput">Nome do Banco:</label>
									<input type="text" class="form-control" id="dbNameInput" name="dbNameInput" placeholder="Digite o nome do banco" value="<?php echo $_SESSION['bancoDeDados']['nomeBanco'] ?? ''; ?>" required>
								</div>
						</div>
						<div class="modal-footer">
						<button type="submit" name="configServer" class="btn btn-primary">Salvar</button>
						<a href="?page=destroySessoes" class="btn btn-danger">Excluir Dados</a>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
						</div>
						</form>
					</div>
				</div>
			</div>
			<form action="" method="post" name="gerarTabela">
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
					<?php
						// Verifica se a sessão existe e contém dados suficientes
						if (isset($_SESSION['bancoDeDados']) && isset($_SESSION['bancoDeDados']['nomeBanco'])) {
						    $nomeBancoPadrao = $_SESSION['bancoDeDados']['nomeBanco'];
						} else {
						    // Define um valor padrão se a sessão não existir ou não conter dados suficientes
						    $nomeBancoPadrao = null;
						}
						?>
					<input type="text" class="form-control" id="nomeBanco" name="nome_banco" value="<?= $nomeBancoPadrao ?>">
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
							<div class="col checkbox-container">
								<label for="nullos">Vazio: </label>
								<input type="checkbox" class="form-check-input custom-checkbox" name="nullos[]">
							</div>
						</div>
					</div>
				</div>
				<button type="button" class="btn btn-primary" onclick="adicionarCampo()"><i class="fas fa-plus"></i> Adicionar Campo</button>
				<button type="submit" class="btn btn-success" name="gerarTabela"><i class="fas fa-table"></i> Gerar Tabela</button>
			</form>
			<?php if (!empty($resultado1)): ?>
			<div class="resultado"><?php echo $resultado1; ?></div>
			<div class="infoss warning"><?php echo $resultado2; ?></div>
			<?php endif; ?>
		</div>
		
		
		
		
		
		 <!-- Footer -->
		<div class="footer">
		<!-- Adicione ícones de redes sociais -->
		<a href="<?php echo gerarUrlRedeSocial('facebook'); ?>" class="social-icons"><i class="fab fa-facebook"></i></a>
		<a href="<?php echo gerarUrlRedeSocial('twitter'); ?>" class="social-icons"><i class="fab fa-twitter"></i></a>
		<a href="<?php echo gerarUrlRedeSocial('whatsapp'); ?>" class="social-icons"><i class="fab fa-whatsapp"></i></a>
		<a href="<?php echo gerarUrlRedeSocial('instagram'); ?>" class="social-icons"><i class="fab fa-instagram"></i></a>
		<a href="<?php echo gerarUrlRedeSocial('youtube'); ?>" class="social-icons"><i class="fab fa-youtube"></i></a>
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
			           <div class="col checkbox-container">
			<label for="nullos">Vazio: </label>
			<input type="checkbox" class="form-check-input custom-checkbox" name="nullos[]">
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
