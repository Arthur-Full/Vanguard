-- ATENÇÃO: este script remove e recria somente o banco vanguard.
DROP DATABASE IF EXISTS vanguard;

CREATE DATABASE vanguard CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vanguard;

CREATE TABLE instituicoes (
    id_instituicao INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cidade VARCHAR(100) DEFAULT NULL,
    cnpj VARCHAR(18) DEFAULT NULL UNIQUE,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE usuarios (
    id_usuario INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_instituicao INT UNSIGNED NOT NULL,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(20) DEFAULT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('admin', 'coordenador', 'professor', 'aluno') NOT NULL,
    status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
    foto_perfil VARCHAR(255) DEFAULT NULL,
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT uq_usuario_email UNIQUE (email),
    CONSTRAINT fk_usuario_instituicao FOREIGN KEY (id_instituicao)
        REFERENCES instituicoes(id_instituicao) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE turmas (
    id_turma INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_instituicao INT UNSIGNED NOT NULL,
    nome VARCHAR(50) NOT NULL,
    serie VARCHAR(30) DEFAULT NULL,
    curso VARCHAR(60) DEFAULT NULL,
    periodo VARCHAR(20) DEFAULT NULL,
    status ENUM('ativa', 'inativa') NOT NULL DEFAULT 'ativa',
    CONSTRAINT uq_turma_instituicao_nome UNIQUE (id_instituicao, nome),
    CONSTRAINT fk_turma_instituicao FOREIGN KEY (id_instituicao)
        REFERENCES instituicoes(id_instituicao) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE disciplinas (
    id_disciplina INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_instituicao INT UNSIGNED NOT NULL,
    nome VARCHAR(80) NOT NULL,
    descricao VARCHAR(255) DEFAULT NULL,
    CONSTRAINT uq_disciplina_instituicao_nome UNIQUE (id_instituicao, nome),
    CONSTRAINT fk_disciplina_instituicao FOREIGN KEY (id_instituicao)
        REFERENCES instituicoes(id_instituicao) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE matriculas (
    id_matricula INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_aluno INT UNSIGNED NOT NULL,
    id_turma INT UNSIGNED NOT NULL,
    data_matricula DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('ativa', 'inativa', 'concluida') NOT NULL DEFAULT 'ativa',
    CONSTRAINT uq_matricula_aluno_turma UNIQUE (id_aluno, id_turma),
    CONSTRAINT fk_matricula_aluno FOREIGN KEY (id_aluno)
        REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_matricula_turma FOREIGN KEY (id_turma)
        REFERENCES turmas(id_turma) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE professor_turmas (
    id_professor_turma INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_professor INT UNSIGNED NOT NULL,
    id_turma INT UNSIGNED NOT NULL,
    id_disciplina INT UNSIGNED NOT NULL,
    CONSTRAINT uq_professor_turma_disciplina UNIQUE (id_professor, id_turma, id_disciplina),
    CONSTRAINT fk_professor_turma_professor FOREIGN KEY (id_professor)
        REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_professor_turma_turma FOREIGN KEY (id_turma)
        REFERENCES turmas(id_turma) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_professor_turma_disciplina FOREIGN KEY (id_disciplina)
        REFERENCES disciplinas(id_disciplina) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE eventos (
    id_evento INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_instituicao INT UNSIGNED NOT NULL,
    id_criador INT UNSIGNED NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT DEFAULT NULL,
    data_inicio DATETIME NOT NULL,
    data_fim DATETIME DEFAULT NULL,
    local VARCHAR(100) DEFAULT NULL,
    publico_alvo ENUM('todos', 'admin', 'coordenador', 'professor', 'aluno') NOT NULL DEFAULT 'todos',
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_eventos_agenda (id_instituicao, data_inicio),
    CONSTRAINT fk_evento_instituicao FOREIGN KEY (id_instituicao)
        REFERENCES instituicoes(id_instituicao) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_evento_criador FOREIGN KEY (id_criador)
        REFERENCES usuarios(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT chk_evento_periodo CHECK (data_fim IS NULL OR data_fim >= data_inicio)
) ENGINE=InnoDB;

-- Permite publicar um evento geral ou restringi-lo a turmas específicas.
CREATE TABLE eventos_turmas (
    id_evento INT UNSIGNED NOT NULL,
    id_turma INT UNSIGNED NOT NULL,
    PRIMARY KEY (id_evento, id_turma),
    CONSTRAINT fk_evento_turma_evento FOREIGN KEY (id_evento)
        REFERENCES eventos(id_evento) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_evento_turma_turma FOREIGN KEY (id_turma)
        REFERENCES turmas(id_turma) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Controle de acesso: o PHP consulta estas permissões além do tipo de usuário.
CREATE TABLE permissoes (
    id_permissao INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(60) NOT NULL,
    descricao VARCHAR(150) NOT NULL,
    CONSTRAINT uq_permissao_codigo UNIQUE (codigo)
) ENGINE=InnoDB;

CREATE TABLE perfil_permissoes (
    tipo_usuario ENUM('admin', 'coordenador', 'professor', 'aluno') NOT NULL,
    id_permissao INT UNSIGNED NOT NULL,
    PRIMARY KEY (tipo_usuario, id_permissao),
    CONSTRAINT fk_perfil_permissao FOREIGN KEY (id_permissao)
        REFERENCES permissoes(id_permissao) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE avisos (
    id_aviso INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_instituicao INT UNSIGNED NOT NULL,
    id_criador INT UNSIGNED NOT NULL,
    titulo VARCHAR(120) NOT NULL,
    mensagem TEXT NOT NULL,
    publico_alvo ENUM('todos', 'admin', 'coordenador', 'professor', 'aluno') NOT NULL DEFAULT 'todos',
    publicado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expira_em DATETIME DEFAULT NULL,
    INDEX idx_avisos_publico_data (id_instituicao, publico_alvo, publicado_em),
    CONSTRAINT fk_aviso_instituicao FOREIGN KEY (id_instituicao)
        REFERENCES instituicoes(id_instituicao) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_aviso_criador FOREIGN KEY (id_criador)
        REFERENCES usuarios(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Registra sessões e permite logout seguro/revogação de acesso pelo ADM.
CREATE TABLE sessoes_login (
    id_sessao BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    token_hash CHAR(64) NOT NULL,
    ip VARCHAR(45) DEFAULT NULL,
    user_agent VARCHAR(255) DEFAULT NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expira_em DATETIME NOT NULL,
    encerrado_em DATETIME DEFAULT NULL,
    CONSTRAINT uq_sessao_token UNIQUE (token_hash),
    INDEX idx_sessao_usuario (id_usuario, expira_em),
    CONSTRAINT fk_sessao_usuario FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE recuperacoes_senha (
    id_recuperacao BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    token_hash CHAR(64) NOT NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expira_em DATETIME NOT NULL,
    utilizado_em DATETIME DEFAULT NULL,
    CONSTRAINT uq_recuperacao_token UNIQUE (token_hash),
    INDEX idx_recuperacao_usuario (id_usuario, expira_em),
    CONSTRAINT fk_recuperacao_usuario FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE atividades (
    id_atividade INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_professor INT UNSIGNED NOT NULL,

INSERT INTO professor_turmas (id_professor, id_turma, id_disciplina) VALUES (2, 1, 1);
INSERT INTO matriculas (id_aluno, id_turma) VALUES (3, 1);

INSERT INTO permissoes (id_permissao, codigo, descricao) VALUES
    (1, 'usuarios.gerenciar', 'Criar, editar e inativar usuários'),
    (2, 'turmas.gerenciar', 'Criar e alterar turmas e disciplinas'),
    (3, 'calendario.gerenciar', 'Criar, editar e excluir eventos'),
    (4, 'atividades.criar', 'Publicar atividades para as turmas'),
    (5, 'atividades.avaliar', 'Avaliar entregas de alunos'),
    (6, 'atividades.entregar', 'Enviar atividades próprias'),
    (7, 'avisos.publicar', 'Publicar avisos institucionais'),
    (8, 'agenda.visualizar', 'Visualizar calendário e avisos');

INSERT INTO perfil_permissoes (tipo_usuario, id_permissao) VALUES
    ('admin', 1), ('admin', 2), ('admin', 3), ('admin', 4), ('admin', 5), ('admin', 6), ('admin', 7), ('admin', 8),
    ('coordenador', 2), ('coordenador', 3), ('coordenador', 7), ('coordenador', 8),
    ('professor', 4), ('professor', 5), ('professor', 8),
    ('aluno', 6), ('aluno', 8);

INSERT INTO eventos (id_instituicao, id_criador, titulo, descricao, data_inicio, data_fim, local, publico_alvo) VALUES
    (1, 1, 'Boas-vindas Vanguard', 'Evento disponível para todos os perfis.', '2026-11-03 19:00:00', '2026-11-03 20:00:00', 'Auditório', 'todos');

INSERT INTO avisos (id_instituicao, id_criador, titulo, mensagem, publico_alvo) VALUES
    (1, 1, 'Bem-vindos ao Vanguard', 'Use sua conta para acompanhar eventos, avisos e atividades.', 'todos');

-- Credencial de banco usada pela aplicação PHP local.
CREATE USER IF NOT EXISTS 'curri'@'%' IDENTIFIED BY '123';
ALTER USER 'curri'@'%' IDENTIFIED BY '123';
