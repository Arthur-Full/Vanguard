<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);

if (($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'POST') && !empty($dados['id_evento'])) {
    try {
        $pdo = getConnection();

        $sql = "UPDATE eventos
                SET titulo = :titulo,
                    descricao = :descricao,
                    data_inicio = :data_inicio,
                    data_fim = :data_fim,
                    local = :local,
                    publico_alvo = :publico_alvo
                WHERE id_evento = :id_evento";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_evento' => $dados['id_evento'],
            ':titulo' => $dados['titulo'],
            ':descricao' => $dados['descricao'] ?? null,
            ':data_inicio' => $dados['data_inicio'],
            ':data_fim' => $dados['data_fim'] ?? null,
            ':local' => $dados['local'] ?? null,
            ':publico_alvo' => $dados['publico_alvo'] ?? null,
        ]);

        echo json_encode(['sucesso' =>true, 'mensagem' => 'Evento atualizado com sucesso!']);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao atualizar evento: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'ID do evento é obrigatorio para atualização.']);
}
?>