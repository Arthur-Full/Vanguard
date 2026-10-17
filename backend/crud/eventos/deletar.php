<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);
$idEvento = $dados['id_evento'] ?? $_GET['id_evento'] ?? null;

if (($_SERVER['REQUEST_METHOD'] === 'DELETE' || $_SERVER['REQUEST_METHOD'] === 'POST') && $idEvento) {
    try {
        $pdo = getConnection();

        $sql = "DELETE FROM eventos WHERE id_evento = :id_evento";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_evento' => $idEvento]);

        echo json_encode(['sucesso' => true, 'mensagem' => 'Evento excluído com sucesso!']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao excluir evento: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'ID do evento não informado.']);
}
?>