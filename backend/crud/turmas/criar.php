<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($dados)){
    try {
        $pdo = getConnection();
        $sql = "INSERT INTO turmas (id_instituicao, nome, serie, curso, periodo) 
                VALUES (:id_instituicao, :nome, :serie, :curso, :periodo)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_instituicao' => $dados['id_instituicao'],
            ':nome' => $dados['nome'],
            ':serie' => $dados['serie'] ?? null,
            ':curso' => $dados['curso'] ?? null,
            ':periodo' => $dados['periodo'] ?? null
        ]);
        echo json_encode(['sucesso' => true, 'mensagem' => 'Turma criada com sucesso!', 'id_turma' => $pdo->lastInsertId()]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao criar turma: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'Requisição inválida ou dados ausentes.']);
}
?>