<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);

if (($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'POST') && !empty($dados['id_atividade'])) {
    try {
        $pdo = getConnection();

        $sql = "UPDATE atividades SET titulo = :titulo, descricao = :descricao, data_entrega = :data_entrega WHERE id_atividade = :id_atividade";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([    
            ':id_atividade' => $dados['id_atividade'],
            ':titulo' => $dados['titulo'],
            ':descricao' => $dados['descricao'] ?? null,
            ':data_entrega' => $dados['data_entrega'] ?? null
        ]);
        
        echo json_encode(['sucesso' =>true, 'mensagem' => 'Atividade atualizada com sucesso!']);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao atualizar atividade: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'ID da atividade é obrigatorio.']);
}
?>