<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);
$idTurma = $dados['id_turma'] ?? $_GET['id_turma'] ?? null;

if (($_SERVER['REQUEST_METHOD'] === 'DELETE' || $_SERVER['REQUEST_METHOD'] === 'POST') && $idTurma) {
    try {
        $pdo = getConnection();

        $sql = "DELETE FROM turmas WHERE id_turma = :id_turma";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_turma' => $idTurma]);

        echo json_encode(['sucesso' => true, 'mensagem' => 'Turma excluída com sucesso!']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao excluir turma: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'ID da turma não informado.']);
}
?>