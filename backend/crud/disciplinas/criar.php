<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);

if (!empty($dados['nome'])){
    try {
        $pdo = getConnection();
    
        $stmt = $pdo->prepare("INSERT INTO disciplinas (nome, descricao) 
                VALUES (:nome, :descricao )");
        $stmt->execute([
            ':nome' => $dados['nome'],
            ':descricao' => $dados['descricao'] ?? null,
        ]);
        echo json_encode(['sucesso' => true, 'mensagem' => 'Disciplina cadastrada!', 'id_disciplina' => $pdo->lastInsertId()]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao cadastrar disciplina: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'Nome da disciplina é obrigatória.']);
}
?>