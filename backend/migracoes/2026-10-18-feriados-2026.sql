CREATE TABLE IF NOT EXISTS feriados (
    id_feriado INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ano SMALLINT UNSIGNED NOT NULL,
    data DATE NOT NULL,
    titulo VARCHAR(120) NOT NULL,
    tipo ENUM('Feriado nacional', 'Ponto facultativo') NOT NULL,
    observacao VARCHAR(160) DEFAULT NULL,
    CONSTRAINT uq_feriado_ano_data_titulo UNIQUE (ano, data, titulo),
    INDEX idx_feriados_data (data)
) ENGINE=InnoDB;

INSERT INTO feriados (ano, data, titulo, tipo, observacao) VALUES
    (2026, '2026-01-01', 'Confraternização Universal', 'Feriado nacional', NULL),
    (2026, '2026-02-16', 'Carnaval', 'Ponto facultativo', NULL),
    (2026, '2026-02-17', 'Carnaval', 'Ponto facultativo', NULL),
    (2026, '2026-02-18', 'Quarta-Feira de Cinzas', 'Ponto facultativo', 'Até as 14h'),
    (2026, '2026-04-03', 'Paixão de Cristo', 'Feriado nacional', NULL),
    (2026, '2026-04-20', 'Véspera de Tiradentes', 'Ponto facultativo', NULL),
    (2026, '2026-04-21', 'Tiradentes', 'Feriado nacional', NULL),
    (2026, '2026-05-01', 'Dia Mundial do Trabalho', 'Feriado nacional', NULL),
    (2026, '2026-06-04', 'Corpus Christi', 'Ponto facultativo', NULL),
    (2026, '2026-06-05', 'Ponto facultativo', 'Ponto facultativo', NULL),
    (2026, '2026-09-07', 'Independência do Brasil', 'Feriado nacional', NULL),
    (2026, '2026-10-12', 'Nossa Senhora Aparecida', 'Feriado nacional', NULL),
    (2026, '2026-10-28', 'Dia do Servidor Público Federal', 'Ponto facultativo', NULL),
    (2026, '2026-11-02', 'Finados', 'Feriado nacional', NULL),
    (2026, '2026-11-15', 'Proclamação da República', 'Feriado nacional', NULL),
    (2026, '2026-11-20', 'Dia Nacional de Zumbi e da Consciência Negra', 'Feriado nacional', NULL),
    (2026, '2026-12-24', 'Véspera de Natal', 'Ponto facultativo', 'Após as 13h'),
    (2026, '2026-12-25', 'Natal', 'Feriado nacional', NULL),
    (2026, '2026-12-31', 'Véspera de Ano Novo', 'Ponto facultativo', 'Após as 13h')
ON DUPLICATE KEY UPDATE tipo=VALUES(tipo), observacao=VALUES(observacao);
