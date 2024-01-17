<?php
// Inicie a sessão
session_start();
// OS CAMPOS CONFIGURE DA MELHOR FOMRA... 
// VOCE PODE POR VARIOS ATALHOS TLG?


function SelectOptions($name) {
    $options = array(
        "P" => "-- PRONTOS --",
        "INT(11) PRIMARY KEY" => "ID",
        "VARCHAR(100)" => "TÍTULO",
        "VARCHAR(255) UNIQUE" => "EMAIL",
        "VARCHAR(255)" => "NOME",
        "TEXT" => "DESCRIÇÃO",
        "VARCHAR(255)" => "USUÁRIO",
        "VARCHAR(255)" => "SENHA",
        "INT" => "QUANTIDADE",
        "DECIMAL(10,2)" => "PREÇO",
        "DATE" => "DATA",
        "DATETIME" => "DATA E HORA",
        "BOOL" => "ATIVO",
        "ENUM('M', 'F')" => "GÊNERO",
        "O" => " -- ORIGINAIS --",
        "INT PRIMARY KEY" => "INT PRIMARY KEY",
        "VARCHAR(255)" => "VARCHAR(255)",
        "TEXT" => "TEXT",
        "FLOAT" => "FLOAT",
        "DOUBLE" => "DOUBLE",
        "DECIMAL" => "DECIMAL",
        "TINYINT" => "TINYINT",
        "SMALLINT" => "SMALLINT",
        "MEDIUMINT" => "MEDIUMINT",
        "BIGINT" => "BIGINT",
        "ENUM('value1', 'value2', ...)" => "ENUM('value1', 'value2', ...)",
        "UNIQUE" => "CAMPO ÚNICO"
    );

    $select = "<select class='form-control' name='$name' required>\n";
    foreach ($options as $value => $label) {
        $select .= "<option value='$value'>$label</option>\n";
    }
    $select .= "</select>\n";

    return $select;
}




   


function gerarUrlRedeSocial($redeSocial) {
    // Mapeamento de ícones para URLs de redes sociais
    $mapaRedesSociais = array(
        'facebook' => 'https://www.facebook.com/CarlosAndreTMJ/',
        //'twitter' => 'https://twitter.com/seuUsuario',
        'whatsapp' => 'https://wa.me/5521970854952',
        //'instagram' => 'https://www.instagram.com/seuUsuario',
        //'youtube' => 'https://www.youtube.com/seuCanal'
    );

    // Verifica se a rede social está no mapa
    if (array_key_exists($redeSocial, $mapaRedesSociais)) {
        return $mapaRedesSociais[$redeSocial];
    }

    // Se não estiver no mapa, retorna uma URL padrão
    return '#';
}


// Função para inserir ou obter dados do banco de dados na sessão
function setarDadosSessaoBanco($db_server, $db_user, $db_senha, $db_nome) {
    // Inicie a sessão se não estiver iniciada
    if (!isset($_SESSION)) {
        session_start();
    }

    // Limpe os dados da sessão
    unset($_SESSION['bancoDeDados']);

    // Atribua os novos dados à sessão
    $_SESSION['bancoDeDados'] = array(
        'servidor' => $db_server,
        'usuario' => $db_user,
        'senha' => $db_senha,
        'nomeBanco' => $db_nome
    );

    // Retorne uma mensagem de alerta
    echo '<script>alert("Dados do banco de dados salvos com sucesso!");</script>';
}

// Verifique se a página é definida na URL e se é a página de exclusão
if (isset($_GET['page']) && $_GET['page'] === 'excluirDados') {
    // Inicie a sessão se não estiver iniciada
    if (!isset($_SESSION)) {
        session_start();
    }

    // Exclua os dados da sessão
    unset($_SESSION['bancoDeDados']);

    // Redirecione para a página principal após 2 segundos
    echo '<script>alert("Dados do banco de dados excluídos com sucesso!"); setTimeout(function(){ window.location.href = "tabelas.php"; }, 1000);</script>';
    exit;
}

// Se você deseja destruir todas as sessões
if (isset($_GET['page']) && $_GET['page'] === 'destroySessoes') {
    // Inicie a sessão se não estiver iniciada
    if (!isset($_SESSION)) {
        session_start();
    }

    // Destrua todas as sessões
    session_destroy();

    // Redirecione para a página principal após 2 segundos
    echo '<script>alert("Todas as sessões foram destruídas com sucesso!"); setTimeout(function(){ window.location.href = "tabelas.php"; }, 1000);</script>';
    exit;
}
?>
