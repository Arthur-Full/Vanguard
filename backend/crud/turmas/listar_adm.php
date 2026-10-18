<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

try {
    $pdo = getConnection();
    $sql = "SELECT t.id_turma, t.id_instituicao, t.nome, t.periodo, i.nome AS nome_instituicao,
                   COUNT(m.id_matricula) AS alunos_total,
                   COALESCE(SUM(CASE WHEN LOWER(COALESCE(m.status, 'ativa')) = 'ativa' THEN 1 ELSE 0 END), 0) AS alunos_ativos,
                   COALESCE(SUM(CASE WHEN LOWER(COALESCE(m.status, 'ativa')) <> 'ativa' THEN 1 ELSE 0 END), 0) AS alunos_inativos
            FROM turmas t
            INNER JOIN instituicoes i ON i.id_instituicao = t.id_instituicao
            LEFT JOIN matriculas m ON m.id_turma = t.id_turma
            GROUP BY t.id_turma, t.id_instituicao, t.nome, t.periodo, i.nome
            ORDER BY t.nome ASC";
    $stmt = $pdo->query($sql);
    echo json_encode(['sucesso' => true, 'dados' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => 'Erro ao listar salas: ' . $e->getMessage()]);
}
?>
