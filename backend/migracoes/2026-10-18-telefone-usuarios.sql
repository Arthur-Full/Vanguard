-- Execute uma vez em bancos Vanguard já criados antes do cadastro de telefone.
ALTER TABLE usuarios ADD COLUMN telefone VARCHAR(20) DEFAULT NULL;
