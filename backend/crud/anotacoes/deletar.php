<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);
$idAnotacao = $dados['id_anotacao'] ?? $_GET['id_anotacao'] ?? null;

if (($_SERVER['REQUEST_METHOD'] === 'DELETE' || $_SERVER['REQUEST_METHOD'] === 'POST') && $idAnotacao) {
    try {
        $pdo = getConnection();

        $stmt = $pdo->prepare("DELETE FROM anotacoes WHERE id_anotacao = :id");
        $stmt->execute([':id' => $idAnotacao]);

        echo json_encode(['sucesso' => true, 'mensagem' => 'Anotação excluída!']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao excluir anotação: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'ID da anotação não informado.']);
}
?>