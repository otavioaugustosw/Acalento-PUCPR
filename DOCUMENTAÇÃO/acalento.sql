CREATE DATABASE IF NOT EXISTS acalento;
USE acalento;

CREATE TABLE IF NOT EXISTS endereco(
    id INT AUTO_INCREMENT PRIMARY KEY,
    rua VARCHAR(50),
    numero SMALLINT, # Pra economizar armazenamento (bibi que ensinou)
    bairro VARCHAR(50),
    cidade VARCHAR(50),
    estado VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_endereco INT NOT NULL,
    email VARCHAR(50),
    senha VARCHAR(256), # É recomendado pela documentação PHP (caso o giu pergunte)
    nome VARCHAR(50),
    cpf VARCHAR(11),
    cnpj VARCHAR(14),
    telefone VARCHAR(11),
    nascimento DATE,
    eh_doador BOOL,
    eh_patrocinador BOOL,
    eh_adm BOOL,
    FOREIGN KEY (id_endereco) REFERENCES endereco(id)
);


CREATE TABLE IF NOT EXISTS despesa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50),
    data date,
    hora time,
    valor DOUBLE,
    link_despesa VARCHAR(256)
);

CREATE TABLE IF NOT EXISTS usuario_gerencia_despesa(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_despesa INT,
    id_usuario INT,
    FOREIGN KEY(id_despesa) REFERENCES despesa(id),
    FOREIGN KEY(id_usuario) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS postagem(
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(50),
    txt_materia VARCHAR(500),
    link_imagem VARCHAR(256),
    datahora TIMESTAMP
);

CREATE TABLE IF NOT EXISTS usuario_realiza_postagem(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_postagem INT,
    id_usuario INT,
    FOREIGN KEY(id_postagem) REFERENCES postagem(id),
    FOREIGN KEY(id_usuario) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS assentamento(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_endereco INT,
    nome VARCHAR(50),
    FOREIGN KEY(id_endereco) REFERENCES endereco(id)
);

CREATE TABLE IF NOT EXISTS evento(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_assentamento INT,
    nome VARCHAR(50),
    descricao VARCHAR(100),
    lotacao_max SMALLINT,
    data DATE,
    hora TIME,
    link_imagem VARCHAR(256)
);

CREATE TABLE IF NOT EXISTS usuario_participa_evento(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_evento INT,
    id_usuario INT,
    presenca_confirmada BOOL,
    FOREIGN KEY(id_evento) REFERENCES evento(id),
    FOREIGN KEY(id_usuario) REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS doacao(
    id INT AUTO_INCREMENT PRIMARY KEY,
    data date,
    evento_destino INT,
    tipo ENUM('Alimentício', 'Brinquedo', 'Limpeza', 'Outros') NOT NULL,
    FOREIGN KEY(evento_destino) REFERENCES evento(id)
);

CREATE TABLE IF NOT EXISTS item(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_doacao INT,
    nome VARCHAR(50),
    quantidade SMALLINT,
    unidade_medida VARCHAR(3),
    valor DOUBLE,
    FOREIGN KEY(id_doacao) REFERENCES doacao(id)
);

CREATE TABLE IF NOT EXISTS usuario_gerencia_doacao(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_doacao INT,
    id_usuario INT,
    FOREIGN KEY(id_doacao) REFERENCES doacao(id),
    FOREIGN KEY(id_usuario) REFERENCES usuario(id)
);
