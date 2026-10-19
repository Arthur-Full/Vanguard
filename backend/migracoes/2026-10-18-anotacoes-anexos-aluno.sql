CREATE TABLE IF NOT EXISTS anotacoes (
    id_anotacao INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    id_turma INT UNSIGNED DEFAULT NULL,
    titulo VARCHAR(120) NOT NULL,
    conteudo TEXT NOT NULL,
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_anotacoes_aluno_turma (id_usuario, id_turma, data_criacao),
    CONSTRAINT fk_anotacao_usuario FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_anotacao_turma FOREIGN KEY (id_turma)
        REFERENCES turmas(id_turma) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS anexos_aluno (
    id_anexo INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    id_turma INT UNSIGNED DEFAULT NULL,
    nome_original VARCHAR(255) NOT NULL,
    caminho VARCHAR(255) NOT NULL,
    tipo_mime VARCHAR(120) NOT NULL,
    tamanho_bytes INT UNSIGNED NOT NULL,
    data_envio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_anexos_aluno_turma (id_usuario, id_turma, data_envio),
    CONSTRAINT fk_anexo_usuario FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_anexo_turma FOREIGN KEY (id_turma)
        REFERENCES turmas(id_turma) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;
