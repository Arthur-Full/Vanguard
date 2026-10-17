<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($dados)){
    try {
        $pdo = getConnection();
        $sql = "INSERT INTO atividades (id_professor, id_turma, titulo, descricao, data_entrega) 
                VALUES (:id_professor, :id_turma, :titulo, :descricao, :data_entrega)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_professor' => $dados['id_professor'],
            ':id_turma' => $dados['id_turma'],
            ':titulo' => $dados['titulo'],
            ':descricao' => $dados['descricao'] ?? null,
            ':data_entrega' => $dados['data_entrega'] ?? null
        ]);
        echo json_encode(['sucesso' => true, 'mensagem' => 'Atividade criada com sucesso!', 'id_atividade' => $pdo->lastInsertId()]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao criar atividade: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'Requisição inválida ou dados ausentes.']);
}
?>