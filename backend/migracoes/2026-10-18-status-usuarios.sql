-- Execute uma vez em bancos Vanguard existentes para habilitar status de usuários.
ALTER TABLE usuarios ADD COLUMN status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo';
