-- Execute uma vez em bancos existentes que ainda usam fk_horario_vinculo.
-- Mantém o nome das disciplinas dos horários já cadastrados antes de liberar
-- o vínculo do horário com qualquer sala ativa da instituição.
ALTER TABLE professor_horarios
    ADD COLUMN disciplina_nome VARCHAR(80) DEFAULT NULL AFTER id_disciplina;

UPDATE professor_horarios AS h
JOIN disciplinas AS d ON d.id_disciplina = h.id_disciplina
SET h.disciplina_nome = d.nome;

ALTER TABLE professor_horarios
    DROP FOREIGN KEY fk_horario_vinculo,
    MODIFY COLUMN id_disciplina INT UNSIGNED DEFAULT NULL,
    ADD CONSTRAINT fk_horario_professor FOREIGN KEY (id_professor)
        REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT fk_horario_turma FOREIGN KEY (id_turma)
        REFERENCES turmas(id_turma) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT fk_horario_disciplina FOREIGN KEY (id_disciplina)
        REFERENCES disciplinas(id_disciplina) ON DELETE SET NULL ON UPDATE CASCADE;
