<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);
$idAtividade = $dados['id_atividade'] ?? $_GET['id_atividade'] ?? null;

if (($_SERVER['REQUEST_METHOD'] === 'DELETE' || $_SERVER['REQUEST_METHOD'] === 'POST') && $idAtividade) {
    try {
        $pdo = getConnection();

        $sql = "DELETE FROM atividades WHERE id_atividade = :id_atividade";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_atividade' => $idAtividade]);

        echo json_encode(['sucesso' => true, 'mensagem' => 'Atividade excluída com sucesso!']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao excluir atividade: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'ID da atividade não informado.']);
}
?>