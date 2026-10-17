<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);
$id = $dados['id_instituicao'] ?? $_GET['id_instituicao'] ?? null;

if ($id) {
    try {
        $pdo = getConnection();

        $stmt = $pdo->prepare("DELETE FROM instituicoes WHERE id_instituicao = :id");
        $stmt->execute([':id' => $id]);

        echo json_encode(['sucesso' => true, 'mensagem' => 'Instituição excluída!']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao excluir instituição: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'ID da instituição não informado.']);
}
?>