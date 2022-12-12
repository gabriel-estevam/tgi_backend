# Backend do Projeto Sistema para Controle de Estoque de Materiais de Limpeza



## Backend do projeto desenvolvido para a Prefeitura de Estância Turística de Salto SP


## Objetivo

O setor de Administração da Prefeitura de Salto necessitava ter um controle de estoque inteligente e prático, onde fosse possivel acompanhar toda movimentação de estoque dos materiais de limpeza de todas as escolas do municipio de Salto. Com base nisso, desenvolvemos API'S em PHP para atender sua necessidade.


### Instalação e Configuração do Servidor Apache para funcionamento das API'S

Recomedamos que seja instalado e configurado o PHP e o banco de dados MYSQL no servidor de aplicação de forma individual e manualmente. No entanto para efeito de simulação do sistema instale o **XAMPP Control Panel**, para isso acesso o link https://www.apachefriends.org/pt_br/download.html faça download.

- Após instalar o XAMMP acesse a pasta ```C:\xampp\htdocs``` crie um pasta chamada "_old" recorte todos os arquivos e diretórios presentes no diretório ```htdocs``` e cole dentro do diretório ```_old```.
- Na sequência, cole/clone o repositorio backend dentro do diretório ```C:\xampp\htdocs```, volte para o diretório "htdocs" e renomeie a pasta **"backend"**  para **"tgi_backend"**
- Acesse a pasta ```C:\xampp``` e execute o aplicativo **xampp-control.exe**, na sequência irá abrir essa janela https://i.imgur.com/0lMP3Qh.png e clique no botão start do item **Apache** e **MySQL**

- Acesse o banco de dados e execute os seguntes scripts:
```
CREATE DATABASE tgidb;

USE tgidb;

CREATE TABLE usuario (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(30) NOT NULL,
    login VARCHAR(20) NOT NULL,
    senha VARCHAR(40) NOT NULL,
    nivel_acesso INT NOT NULL,
    usuario_ativo INT NOT NULL,
    usuario_deletado INT NOT NULL,
    fk_unidade_negocio INT
);

CREATE TABLE unidade_negocio (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(30) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    unidade_negocio_deletado INT NOT NULL,
    unidade_negocio_ativo INT NOT NULL,
    fk_usuario INT,
    fk_endereco INT NOT NULL
);

CREATE TABLE fornecedor (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(30) NOT NULL,
    fornecedor_ativo INT NOT NULL,
    fornecedor_deletado INT NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    fk_representante INT NOT NULL,
    fk_endereco INT NOT NULL
);

CREATE TABLE estoque_almoxarifado (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    qtde_minima INT NOT NULL,
    qtde_ideal INT NOT NULL,
    qtde_atual INT NOT NULL,
    qtde_maxima INT NOT NULL,
    dt_entrada DATETIME NOT NULL,
    estoque_almoxarifado_ativo INT NOT NULL,
	fk_produto INT NOT NULL,
    fk_fornecedor INT NOT NULL,
    fk_usuario_entrada INT NOT NULL
);

CREATE TABLE endereco (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    logradouro VARCHAR(30) NOT NULL,
    cep VARCHAR(10) NOT NULL,
    numero INT NOT NULL,
    complemento VARCHAR(30),
    bairro VARCHAR(30) NOT NULL,
    cidade VARCHAR(30) NOT NULL,
    estado VARCHAR(30) NOT NULL,
    endereco_ativo INT NOT NULL,
    endereco_deletado INT NOT NULL
);

CREATE TABLE estoque_unidade_negocio (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    qtde_minima INT NOT NULL,
    qtde_ideal INT NOT NULL,
    qtde_atual INT NOT NULL,
    qtde_maxima INT NOT NULL,
    dt_entrada DATETIME NOT NULL,
    fk_unidade_negocio INT NOT NULL,
    fk_produto INT NOT NULL
);

CREATE TABLE movimentacao_estoque (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    dt_movimentacao DATETIME NOT NULL,
    qtde_movimentada INT NOT NULL,
    tipo_movimentacao INT NOT NULL,
    fk_usuario INT NOT NULL,
    fk_estoque_unidade_negocio INT NOT NULL,
    fk_estoque_almoxarifado INT
);

CREATE TABLE estoque_almoxarifado_movimentacao_estoque (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fk_estoque_almoxarifado INT NOT NULL,
    fk_movimentacao_estoque INT NOT NULL
);

CREATE TABLE estoque_unidade_negocio_movimentacao_estoque (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fk_movimentacao_estoque INT NOT NULL,
    fk_estoque_unidade_negocio INT NOT NULL
);

CREATE TABLE produto (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(30) NOT NULL,
    produto_ativo INT NOT NULL,
    produto_deletado INT NOT NULL,
    fk_unidade_medida INT NOT NULL
);

CREATE TABLE unidade_medida (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    unidade_medida_ativo INT NOT NULL,
    unidade_medida_deletado INT NOT NULL,
    unidade_medida VARCHAR(15) NOT NULL
);

CREATE TABLE representante (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(30) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(30) NOT NULL,
    representante_ativo INT NOT NULL,
    representante_deletado INT NOT NULL
);

CREATE TABLE solicitacao (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fk_produto INT NOT NULL,
    qtde INT NOT NULL,
    fk_unidade_medida INT NOT NULL,
    observacoes VARCHAR(255),
    fk_usuario INT NOT NULL,
    fk_unidade_negocio INT NOT NULL,
    solicitacao_deletado INT NOT NULL
);

SELECT * FROM usuario;

SELECT * FROM unidade_negocio;

SELECT * FROM endereco;

SELECT count(id) AS qtde_usuarios FROM usuario WHERE login = 'teste2' AND usuario_deletado = 1;
INSERT INTO `tgidb`.`usuario` (`nome`, `login`, `senha`, `nivel_acesso`, `usuario_ativo`, `usuario_deletado`) VALUES ('Guilherme Goedtel', 'guilherme', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '0');
INSERT INTO `tgidb`.`usuario` (`nome`, `login`, `senha`, `nivel_acesso`, `usuario_ativo`, `usuario_deletado`) VALUES ('Robert Granja', 'robert', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '0');


ALTER TABLE `tgidb`.`usuario` 
CHANGE COLUMN `nome` `nome` VARCHAR(50) NOT NULL ,
CHANGE COLUMN `login` `login` VARCHAR(200) NOT NULL ,
CHANGE COLUMN `senha` `senha` VARCHAR(200) NOT NULL ;


ALTER TABLE `tgidb`.`endereco` 
CHANGE COLUMN `logradouro` `logradouro` VARCHAR(300) NOT NULL ,
CHANGE COLUMN `cep` `cep` VARCHAR(11) NOT NULL ,
CHANGE COLUMN `bairro` `bairro` VARCHAR(50) NOT NULL ,
CHANGE COLUMN `cidade` `cidade` VARCHAR(100) NOT NULL ;


ALTER TABLE `tgidb`.`unidade_negocio` 
CHANGE COLUMN `nome` `nome` VARCHAR(300) NOT NULL ,
CHANGE COLUMN `telefone` `telefone` VARCHAR(30) NOT NULL ;


ALTER TABLE `tgidb`.`representante` 
CHANGE COLUMN `nome` `nome` VARCHAR(200) NOT NULL ,
CHANGE COLUMN `telefone` `telefone` VARCHAR(30) NOT NULL ,
CHANGE COLUMN `email` `email` VARCHAR(100) NOT NULL ;


ALTER TABLE `tgidb`.`fornecedor` 
CHANGE COLUMN `nome` `nome` VARCHAR(200) NOT NULL ,
CHANGE COLUMN `telefone` `telefone` VARCHAR(30) NOT NULL ;


ALTER TABLE `tgidb`.`produto` 
CHANGE COLUMN `nome` `nome` VARCHAR(300) NOT NULL ;


ALTER TABLE `tgidb`.`unidade_medida` 
CHANGE COLUMN `unidade_medida` `unidade_medida` VARCHAR(100) NOT NULL ;
```

## Testando Conexão com API
Para Testar a conexão da API recomendamos instalar o Postman, acesse o site oficial do Postman e realize a instalação: https://www.postman.com/

<br>
Após instalar a ferramenta, realize a importação do arquivo JSON de coleção das Api's. Esse arquivo encontra-se presente no caminho do projeto ```C:\xampp\htdocs\tgi_backend``` TGI - CEUNSP.postman_collection.
- Coleção Importada: https://i.imgur.com/Qi6M046.png

<br>
Antes de testar a conexão, verifique se a o Apache e o banco de dados MYSQL estão ativos, se estiver usando o xampp verifique o painel do mesmo.

<br>
Com o Postman aberto, acesse o diretório "login" clique na rota "login" POST veifique se a URL contem \tgi_backend\NOME_API
clique em send. 
Com isso, será gerado um **Token JWT**. Esse token tem validade de 6 horas e **deve ser usados no cabeçalho (headers)** das demais rotas da API. https://i.imgur.com/qP9kjpj.png