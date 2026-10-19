CREATE TABLE IF NOT EXISTS lembretes_professor (
    id_lembrete INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_professor INT UNSIGNED NOT NULL,
    titulo VARCHAR(120) NOT NULL,
    descricao TEXT DEFAULT NULL,
    data_lembrete DATETIME NOT NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_lembrete_professor_data (id_professor, data_lembrete),
    CONSTRAINT fk_lembrete_professor FOREIGN KEY (id_professor)
        REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS professor_horarios (
    id_horario INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_professor INT UNSIGNED NOT NULL,
    id_turma INT UNSIGNED NOT NULL,
    id_disciplina INT UNSIGNED NOT NULL,
    dia_semana TINYINT UNSIGNED NOT NULL COMMENT '0=domingo ... 6=sábado',
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_professor_horario (id_professor, dia_semana, hora_inicio),
    CONSTRAINT fk_horario_vinculo FOREIGN KEY (id_professor,id_turma,id_disciplina)
        REFERENCES professor_turmas(id_professor,id_turma,id_disciplina) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT chk_horario_dia CHECK (dia_semana BETWEEN 0 AND 6),
    CONSTRAINT chk_horario_periodo CHECK (hora_fim > hora_inicio)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS avaliacoes_alunos (
    id_avaliacao INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_professor INT UNSIGNED NOT NULL,
    id_aluno INT UNSIGNED NOT NULL,
    id_turma INT UNSIGNED NOT NULL,
    tipo ENUM('atividade','trabalho','prova') NOT NULL,
    titulo VARCHAR(120) NOT NULL,
    nota DECIMAL(4,2) NOT NULL,
    data_avaliacao DATE NOT NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_avaliacao_aluno_turma (id_aluno, id_turma, tipo, data_avaliacao),
    CONSTRAINT fk_avaliacao_professor FOREIGN KEY (id_professor)
        REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_avaliacao_aluno FOREIGN KEY (id_aluno)
        REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_avaliacao_turma FOREIGN KEY (id_turma)
        REFERENCES turmas(id_turma) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT chk_avaliacao_nota CHECK (nota BETWEEN 0 AND 10)
) ENGINE=InnoDB;
