<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);

if (($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'POST') && !empty($dados['id_turma'])) {
    try {
        $pdo = getConnection();

        $sql = "UPDATE turmas SET nome = :nome, serie = :serie, curso = :curso, periodo = :periodo WHERE id_turma = :id_turma";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([    
            ':id_turma' => $dados['id_turma'],
            ':nome' => $dados['nome'],
            ':serie' => $dados['serie'] ?? null,
            ':curso' => $dados['curso'] ?? null,
            ':periodo' => $dados['periodo'] ?? null
        ]);
        
        echo json_encode(['sucesso' =>true, 'mensagem' => 'Turma atualizada com sucesso!']);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao atualizar Turma: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'ID da turma é obrigatorio.']);
}
?>