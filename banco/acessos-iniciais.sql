-- Cria/recupera a instituição local e três contas demonstrativas sem recriar o banco.
INSERT INTO instituicoes (nome, cidade, cnpj)
VALUES ('Vanguard Escola', 'São Paulo', '00000000000191')
ON DUPLICATE KEY UPDATE id_instituicao=LAST_INSERT_ID(id_instituicao);

SET @id_instituicao_vanguard = (SELECT id_instituicao FROM instituicoes WHERE cnpj='00000000000191' LIMIT 1);
SET @senha_hash_vanguard = '$2y$10$gWjUSgQsBTBjJagT34Cy1.fie7ifYztUom8iGjFQywfyr5fT17pKm';

INSERT INTO usuarios (id_instituicao,nome,email,senha_hash,tipo_usuario,status) VALUES
    (@id_instituicao_vanguard,'Coordenação Vanguard','admin@vanguard.local',@senha_hash_vanguard,'admin','ativo'),
    (@id_instituicao_vanguard,'Professor Vanguard','professor@vanguard.local',@senha_hash_vanguard,'professor','ativo'),
    (@id_instituicao_vanguard,'Aluno Vanguard','aluno@vanguard.local',@senha_hash_vanguard,'aluno','ativo')
ON DUPLICATE KEY UPDATE id_instituicao=VALUES(id_instituicao),senha_hash=VALUES(senha_hash),status='ativo';
