# GeradorDeTabelas
Desenvolva Tabelas de Banco de Dados Rapidamente com Este Gerador PHP/SQL/Python!

## Gerador de Tabelas

O **Gerador de Tabelas** é uma ferramenta desenvolvida em PHP que permite criar rapidamente scripts para a criação de tabelas em bancos de dados MySQL. Com esta aplicação web, você pode definir a estrutura da sua tabela, selecionar o tipo de arquivo desejado (PHP, SQL, Python) e, em seguida, gerar automaticamente o script correspondente.

### Funcionalidades

- **Seleção de Tipo de Arquivo:**
  - Escolha entre PHP, SQL e Python como tipos de arquivo para gerar os scripts.

- **Definição de Campos da Tabela:**
  - Adicione campos à tabela, informando o nome, tipo e se é permitido ser nulo.

- **Adição Dinâmica de Campos:**
  - Utilize a funcionalidade de adicionar campos dinamicamente para facilitar a criação de tabelas com muitos campos.

- **Criação de Tabelas em PHP:**
  - Gere scripts PHP prontos para serem executados, facilitando a integração com seus projetos PHP.

- **Criação de Tabelas em SQL:**
  - Gere scripts SQL para criar a tabela diretamente no banco de dados MySQL.

- **Criação de Tabelas em Python:**
  - Gere scripts Python para criar a tabela usando PyMySQL.

- **Suporte a Bancos de Dados MySQL:**
  - O script gerado é otimizado para execução em bancos de dados MySQL.

- **Feedback Visual:**
  - Receba mensagens de feedback sobre o sucesso ou falha na geração do script.

- **Diretório de Salvamento:**
  - Os scripts gerados são salvos em um diretório específico (`tabelas/`), facilitando o gerenciamento.

### Como Utilizar

1. Acesse a aplicação web, (Update > e insira a configuraçao do banco de dados na opçao da navbar)
2. Insira o nome da tabela.
3. Escolha o tipo de arquivo (PHP, SQL ou Python).
4. Adicione os campos da tabela, informando nome, tipo e se é permitido ser nulo.
5. Clique em "Gerar Tabela" para obter o script.

### Pré-requisitos

- Servidor web com suporte a PHP (pode ser usado XAMPP, WAMP, MAMP, etc.).

### Instalação

1. Clone ou faça o download deste repositório para o seu servidor web.
2. Certifique-se de que o PHP esteja configurado corretamente.
3. Acesse a aplicação pelo navegador.

### Estrutura do Projeto

- `Tabelas.php`: Página principal da aplicação.
- `tabelas_config.php`: Configuraçoes PHP para geração de scripts.
- `tabelas_functions.php`: Funções PHP para geração de scripts.
- `saida/`: Diretório onde os scripts gerados serão salvos.

### Autor

Este projeto foi desenvolvido por [CarlosTMJ].

### Licença

Este projeto é distribuído sob a licença [Licensa Gratuita mano!]. Consulte o arquivo `LICENSE` para obter mais informações.
