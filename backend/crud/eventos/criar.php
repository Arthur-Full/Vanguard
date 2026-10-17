<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($dados)){
    try {
        $pdo = getConnection();

        $sql = "INSERT INTO eventos (id_instituicao, titulo, descricao, data_inicio, data_fim, local, publico_alvo) 
                VALUES (:id_instituicao, :titulo, :descricao, :data_inicio, :data_fim, :local, :publico_alvo)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_instituicao' => $dados['id_instituicao'],
            ':titulo' => $dados['titulo'],
            ':descricao' => $dados['descricao'] ?? null,
            ':data_inicio' => $dados['data_inicio'],
            ':data_fim' => $dados['data_fim'] ?? null,
            ':local' => $dados['local'] ?? null,
            ':publico_alvo' => $dados['publico_alvo'] ?? null
        ]);
        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Evento criado com sucesso!',
            'id_evento' => $pdo->lastInsertId()
        ]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao criar evento: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'Requisição inválida ou dados ausentes.']);
}
?>